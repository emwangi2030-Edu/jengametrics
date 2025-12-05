<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PurchasesReportController extends Controller
{
    public function __invoke(Request $request)
    {
        $materialsQuery = Material::with('supplier')
            ->whereProjectId(project_id())
            ->orderByDesc('created_at');

        $year = $request->input('year');
        $month = $request->input('month');

        if ($year) {
            $materialsQuery->whereYear('created_at', $year);
        }
        if ($month) {
            $materialsQuery->whereMonth('created_at', $month);
        }

        $materials = $materialsQuery->get();

        $headers = [
            'Material',
            'Supplier',
            'Unit',
            'Unit Price',
            'Quantity',
            'Total Cost',
            'Date Purchased',
        ];

        $rows = $materials->map(function ($material) {
            $total = (float) ($material->unit_price ?? 0) * (float) ($material->quantity_purchased ?? 0);
            return [
                $material->name ?? 'Unknown',
                optional($material->supplier)->name ?? 'N/A',
                $material->unit_of_measure ?? 'N/A',
                number_format((float) ($material->unit_price ?? 0), 2, '.', ''),
                number_format((float) ($material->quantity_purchased ?? 0), 2, '.', ''),
                number_format($total, 2, '.', ''),
                optional($material->created_at)?->format('Y-m-d'),
            ];
        });

        if ($request->boolean('download')) {
            $xml = $this->buildSpreadsheetXml($headers, $rows);
            $filename = 'purchases_report_' . now()->format('Ymd_His') . '.xls';

            return Response::make($xml, 200, [
                'Content-Type' => 'application/vnd.ms-excel',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ]);
        }

        $years = Material::whereProjectId(project_id())
            ->selectRaw('DISTINCT YEAR(created_at) as y')
            ->orderByDesc('y')
            ->pluck('y');

        if ($request->ajax()) {
            return view('report.partials.purchases_table', ['materials' => $materials]);
        }

        return view('report.purchases', [
            'materials' => $materials,
            'selectedYear' => $year,
            'selectedMonth' => $month,
            'years' => $years,
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
            <Style ss:ID="sNumber"><NumberFormat ss:Format="0.00"/></Style>
        </Styles>';

        $tableRows = '';
        $tableRows .= '<Row>';
        foreach ($headers as $header) {
            $tableRows .= '<Cell><Data ss:Type="String">' . $this->xmlEscape($header) . '</Data></Cell>';
        }
        $tableRows .= '</Row>';

        foreach ($rows as $row) {
            $tableRows .= '<Row>';
            foreach ($row as $idx => $cell) {
                $cellValue = $cell ?? '';
                $isDate = preg_match('/^\\d{4}-\\d{2}-\\d{2}$/', (string) $cellValue);
                $isNumeric = is_numeric($cellValue) && !$isDate;
                $style = $isDate ? ' ss:StyleID="sDate"' : ($isNumeric ? ' ss:StyleID="sNumber"' : ' ss:StyleID="sText"');
                $type = $isNumeric ? 'Number' : 'String';
                $tableRows .= '<Cell' . $style . '><Data ss:Type="' . $type . '">' . $this->xmlEscape((string) $cellValue) . '</Data></Cell>';
            }
            $tableRows .= '</Row>';
        }

        $table = '<Table>' . $tableRows . '</Table>';
        $worksheet = '<Worksheet ss:Name="Purchases Report">' . $table . '</Worksheet>';
        $workbookClose = '</Workbook>';

        return $xmlHeader . $workbookOpen . $styles . $worksheet . $workbookClose;
    }

    protected function xmlEscape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
