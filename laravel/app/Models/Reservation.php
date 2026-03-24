<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Reservation extends Model
{
    protected $table = 'Reservation';
    protected $primaryKey = 'id_reservation';
    public $timestamps = false;

    protected $fillable = [
        'client_resa',
        'voiture_resa',
        'date_debut',
        'date_fin',
        'statut_reservation',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    // Relations
    public function client(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'client_resa', 'id_utilisateur');
    }

    public function voiture(): BelongsTo
    {
        return $this->belongsTo(Voiture::class, 'voiture_resa', 'id_voiture');
    }

    public function paiement(): HasOne
    {
        return $this->hasOne(Paiement::class, 'paiement_resa', 'id_reservation');
    }

    // Scopes
    public function scopeConfirmees($query)
    {
        return $query->where('statut_reservation', 'confirmée');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut_reservation', 'en attente');
    }

    public function scopeAnnulees($query)
    {
        return $query->where('statut_reservation', 'annulée');
    }

    public function scopeActives($query)
    {
        return $query->where('statut_reservation', 'confirmée')
                     ->where('date_fin', '>=', Carbon::today());
    }

    public function scopeFutures($query)
    {
        return $query->where('statut_reservation', 'confirmée')
                     ->where('date_debut', '>', Carbon::today());
    }

    public function scopePassees($query)
    {
        return $query->where('statut_reservation', 'confirmée')
                     ->where('date_fin', '<', Carbon::today());
    }

    // Methods
    public function duree()
    {
        return $this->date_fin->diffInDays($this->date_debut);
    }

    public function prixTotal()
    {
        return $this->duree() * $this->voiture->prix_jour;
    }

    public function confirmer()
    {
        $this->statut_reservation = 'confirmée';
        return $this->save();
    }

    public function annuler()
    {
        $this->statut_reservation = 'annulée';
        return $this->save();
    }

    public function isActive()
    {
        return $this->statut_reservation === 'confirmée' && $this->date_fin >= Carbon::today();
    }
}
