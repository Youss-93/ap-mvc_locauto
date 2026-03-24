<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voiture extends Model
{
    protected $table = 'Voiture';
    protected $primaryKey = 'id_voiture';
    public $timestamps = false;

    protected $fillable = [
        'id_admin',
        'modele',
        'marque',
        'année',
        'prix_jour',
        'caution',
        'disponibilité',
        'image_loc',
    ];

    protected $casts = [
        'disponibilité' => 'boolean',
        'prix_jour' => 'decimal:2',
        'caution' => 'decimal:2',
        'année' => 'integer',
    ];

    // Relations
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'id_admin', 'id_utilisateur');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'voiture_resa', 'id_voiture');
    }

    // Scopes
    public function scopeDisponibles($query)
    {
        return $query->where('disponibilité', true);
    }

    public function scopeIndisponibles($query)
    {
        return $query->where('disponibilité', false);
    }

    public function scopeByAdmin($query, $id_admin)
    {
        return $query->where('id_admin', $id_admin);
    }

    // Methods
    public function fullName()
    {
        return "{$this->marque} {$this->modele}";
    }

    public function setDisponible($available = true)
    {
        $this->disponibilité = $available;
        return $this->save();
    }
}
