<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;
use Carbon\Carbon;

class RendezVous extends Model
{
    protected $fillable = [
        'dentiste_id',
        'date_heure',
        'motif',
        'statut',
        'patient_id',
    ];
    protected $casts = [
        'date_heure' => 'datetime',
    ];
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function dentiste()
    {
        return $this->belongsTo(Dentist::class, 'dentiste_id');
    }
    public function scopeFilter($query, $filters)
    {
        $selectedDate = $filters['date'] ?? Carbon::today()->format('Y-m-d');
        $dateFilter = Carbon::parse($selectedDate);

        switch ($filters['filter'] ?? 'today') {
            case 'today':
                $query->whereDate('date_heure', $dateFilter);
                break;

            case 'week':
                $query->whereBetween('date_heure', [
                    $dateFilter->copy()->startOfWeek(Carbon::MONDAY),
                    $dateFilter->copy()->endOfWeek(Carbon::SUNDAY),
                ]);
                break;

            case 'month':
                $query->whereMonth('date_heure', $dateFilter->month)
                    ->whereYear('date_heure', $dateFilter->year);
                break;

            case 'all':
                // aucun filtre
                break;
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('nom_complet', 'like', "%$search%")
                    ->orWhere('num_telephone', 'like', "%$search%");
            });
        }

        if (!empty($filters['dentiste_id'])) {
            $query->where('dentiste_id', $filters['dentiste_id']);
        }

        if (!empty($filters['statut'])) {
            $query->where('statut', $filters['statut']);
        }

        return $query;
    }
}
