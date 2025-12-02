<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class WagesReportController extends Controller
{
    public function __invoke(Request $request)
    {
        $payments = Payment::with(['worker' => fn ($q) => $q->withTrashed()])
            ->whereProjectId(project_id())
            ->orderByDesc('payment_date')
            ->get();

        $headers = [
            'Payee',
            'Payment Date',
            'Amount',
            'Period Start',
            'Period End',
            'Status',
        ];

        $rows = $payments->map(function ($payment) {
            $worker = $payment->worker;
            $status = $worker && ($worker->terminated || $worker->trashed()) ? 'Terminated' : 'Active';

            return [
                $worker->full_name ?? 'Unknown',
                optional($payment->payment_date)->format('Y-m-d'),
                number_format((float) $payment->amount, 2, '.', ''),
                optional($payment->period_start)->format('Y-m-d'),
                optional($payment->period_end)->format('Y-m-d'),
                $status,
            ];
        });

        if ($request->boolean('download')) {
            $xml = $this->buildSpreadsheetXml($headers, $rows);
            $filename = 'wages_report_' . now()->format('Ymd_His') . '.xls';

            return Response::make($xml, 200, [
                'Content-Type' => 'application/vnd.ms-excel',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ]);
        }

        return view('report.wages', [
            'payments' => $payments,
            'headers' => $headers,
        ]);
    }

    protected function buildSpreadsheetXml(array $headers, $rows): string
    {
        $xmlHeader = '<?xml version="1.0"?>' .
            '<?mso-application progid="Excel.Sheet"?>';

        $workbookOpen = '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
            xmlns:o="urn:schemas-microsoft-com:office:office"
            xmlns:x="urn:schemas-microsoft-com:office:excel"
            xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">';

        $styles = '<Styles>
            <Style ss:ID="sText"><NumberFormat ss:Format="@"/></Style>
            <Style ss:ID="sDate"><NumberFormat ss:Format="yyyy-mm-dd"/></Style>
        </Styles>';

        $tableRows = '';
        $tableRows .= '<Row>';
        foreach ($headers as $header) {
            $tableRows .= '<Cell><Data ss:Type="String">' . $this->xmlEscape($header) . '</Data></Cell>';
        }
        $tableRows .= '</Row>';

        foreach ($rows as $row) {
            $tableRows .= '<Row>';
            foreach ($row as $cell) {
                $cellValue = $cell ?? '';
                $isDate = preg_match('/^\\d{4}-\\d{2}-\\d{2}$/', (string) $cellValue);
                $type = $isDate ? 'String' : 'String';
                $style = $isDate ? ' ss:StyleID="sDate"' : ' ss:StyleID="sText"';
                $tableRows .= '<Cell' . $style . '><Data ss:Type="' . $type . '">' . $this->xmlEscape((string) $cellValue) . '</Data></Cell>';
            }
            $tableRows .= '</Row>';
        }

        $table = '<Table>' . $tableRows . '</Table>';
        $worksheet = '<Worksheet ss:Name="Wages Report">' . $table . '</Worksheet>';
        $workbookClose = '</Workbook>';

        return $xmlHeader . $workbookOpen . $styles . $worksheet . $workbookClose;
    }

    protected function xmlEscape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
