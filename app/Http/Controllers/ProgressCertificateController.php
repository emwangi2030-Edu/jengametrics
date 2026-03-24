<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProgressCertificateRequest;
use App\Http\Requests\UpdateProgressCertificateRequest;
use App\Models\ProgressCertificate;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressCertificateController extends Controller
{
    public function index(Request $request)
    {
        $projectId = project_id();
        if (! $projectId) {
            return redirect()->route('projects.index')->with('warning', 'Please select or create a project first.');
        }

        $project = Project::findOrFail($projectId);
        $certificates = ProgressCertificate::where('project_id', $projectId)
            ->orderBy('period_start', 'desc')
            ->get();

        return view('progress_certificates.index', compact('project', 'certificates'));
    }

    public function create()
    {
        $projectId = project_id();
        if (! $projectId) {
            return redirect()->route('projects.index')->with('warning', 'Please select or create a project first.');
        }

        $project = Project::findOrFail($projectId);
        return view('progress_certificates.create', compact('project'));
    }

    public function store(StoreProgressCertificateRequest $request)
    {
        $projectId = project_id();
        if (! $projectId) {
            return redirect()->route('projects.index')->with('danger', 'Please select a project first.');
        }

        ProgressCertificate::create(array_merge($request->validated(), [
            'project_id' => $projectId,
            'status' => ProgressCertificate::STATUS_DRAFT,
        ]));

        return redirect()->route('progress_certificates.index')->with('success', 'Progress certificate created.');
    }

    public function edit(ProgressCertificate $progressCertificate)
    {
        $this->authorizeProject($progressCertificate);

        if (! $progressCertificate->isDraft()) {
            return redirect()->route('progress_certificates.index')
                ->with('warning', 'Only draft certificates can be edited.');
        }

        $project = $progressCertificate->project;
        return view('progress_certificates.edit', compact('progressCertificate', 'project'));
    }

    public function update(UpdateProgressCertificateRequest $request, ProgressCertificate $progressCertificate)
    {
        $this->authorizeProject($progressCertificate);

        if (! $progressCertificate->isDraft()) {
            return redirect()->route('progress_certificates.index')
                ->with('danger', 'Only draft certificates can be updated.');
        }

        $progressCertificate->update($request->validated());

        return redirect()->route('progress_certificates.index')->with('success', 'Progress certificate updated.');
    }

    public function markSent(ProgressCertificate $progressCertificate)
    {
        $this->authorizeProject($progressCertificate);

        if (! $progressCertificate->isDraft()) {
            return redirect()->route('progress_certificates.index')
                ->with('warning', 'Only draft certificates can be marked as sent.');
        }

        $progressCertificate->update([
            'status' => ProgressCertificate::STATUS_SENT,
            'sent_at' => now(),
        ]);

        return redirect()->route('progress_certificates.index')->with('success', 'Certificate marked as sent.');
    }

    public function markPaid(ProgressCertificate $progressCertificate)
    {
        $this->authorizeProject($progressCertificate);

        if ($progressCertificate->isPaid()) {
            return redirect()->route('progress_certificates.index')->with('info', 'Certificate is already paid.');
        }

        $progressCertificate->update([
            'status' => ProgressCertificate::STATUS_PAID,
            'paid_at' => now(),
        ]);

        return redirect()->route('progress_certificates.index')->with('success', 'Certificate marked as paid.');
    }

    private function authorizeProject(ProgressCertificate $progressCertificate): void
    {
        $projectId = (int) (Auth::user()->project_id ?? 0);
        $userProjectIds = Auth::user()->projects()->pluck('projects.id')->toArray();
        $allowed = $projectId === (int) $progressCertificate->project_id
            || in_array((int) $progressCertificate->project_id, $userProjectIds, true);

        if (! $allowed) {
            abort(403, 'You do not have access to this certificate.');
        }
    }
}
