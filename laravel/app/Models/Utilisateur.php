<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Utilisateur extends Model
{
    protected $table = 'Utilisateur';
    protected $primaryKey = 'id_utilisateur';
    public $timestamps = false;

    protected $fillable = [
        'nom_utilisateur',
        'prenom_utilisateur',
        'email',
        'mdp_utilisateur',
        'num_tel',
        'role_utilisateur',
    ];

    protected $hidden = [
        'mdp_utilisateur',
    ];

    // Relations
    public function voitures(): HasMany
    {
        return $this->hasMany(Voiture::class, 'id_admin', 'id_utilisateur');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'client_resa', 'id_utilisateur');
    }

    public function paiements()
    {
        return $this->hasManyThrough(Paiement::class, Reservation::class, 'client_resa', 'paiement_resa', 'id_utilisateur', 'id_reservation');
    }

    // Scopes
    public function scopeAdmins($query)
    {
        return $query->where('role_utilisateur', 'admin');
    }

    public function scopeClients($query)
    {
        return $query->where('role_utilisateur', 'client');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->prenom_utilisateur} {$this->nom_utilisateur}";
    }

    public function isAdmin()
    {
        return $this->role_utilisateur === 'admin';
    }
}
