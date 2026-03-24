<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    protected $table = 'Paiement';
    protected $primaryKey = 'id_paiement';
    public $timestamps = false;

    protected $fillable = [
        'paiement_resa',
        'montant',
        'mode_paiement',
        'statut_paiement',
        'date_paiement',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'datetime',
    ];

    // Relations
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'paiement_resa', 'id_reservation');
    }

    public function client()
    {
        return $this->reservation->client;
    }

    // Scopes
    public function scopeValidees($query)
    {
        return $query->where('statut_paiement', 'validée');
    }

    public function scopeEchouees($query)
    {
        return $query->where('statut_paiement', 'échoué');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut_paiement', 'en attente');
    }

    public function scopeByMode($query, $mode)
    {
        return $query->where('mode_paiement', $mode);
    }

    // Methods
    public function valider()
    {
        $this->statut_paiement = 'validée';
        return $this->save();
    }

    public function echouer()
    {
        $this->statut_paiement = 'échoué';
        return $this->save();
    }

    public function pendre()
    {
        $this->statut_paiement = 'en attente';
        return $this->save();
    }

    public function isValidee()
    {
        return $this->statut_paiement === 'validée';
    }

    public function isEchouee()
    {
        return $this->statut_paiement === 'échoué';
    }
}
