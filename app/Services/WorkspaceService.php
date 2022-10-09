<?php

namespace App\Services;

use App\Models\Workspace;

class WorkspaceService
{
	public function getWorkspacesWithInactive()
	{
		return Workspace::all();
	}

	public function getActiveWorkspaces()
	{
		return Workspace::where('inactive', false)
			->orWhereNull('inactive')
			->get();
	}
}
