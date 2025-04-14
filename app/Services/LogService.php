<?php
namespace App\Services;

use App\Data\LogData;
use App\Models\Log;
use App\Services\Interfaces\ILogService;

class LogService implements ILogService
{
    const PER_PAGE = 15;

    public function index(array $request)
    {
        $perPage = $request['per_page'] ?? self::PER_PAGE;

        $query = Log::query();

        if (!empty($request['search'])) {
            $search = '%' . strtolower($request['search']) . '%';
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(message) LIKE ?', [$search]);
            });
        }

        $sortBy = $request['sort_by'] ?: 'created_at';
        $sortDir = $request['sort_dir'] ?: 'asc';

        $query->orderBy($sortBy, $sortDir);

        return $query->paginate($perPage);
    }

    public function create(LogData $logData)
    {
        Log::create([
            'message' => $logData->message
        ]);
    }
}
