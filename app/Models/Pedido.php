<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Pedido extends Model
{
    use HasFactory;

    public const ESTADOS = [
        'pendiente' => 'Pendiente',
        'pagado' => 'Pagado',
        'enviado' => 'Enviado',
        'entregado' => 'Entregado',
        'cancelado' => 'Cancelado',
    ];

    public const METODOS_PAGO = [
        'transferencia' => 'Transferencia bancaria',
        'contra_entrega' => 'Pago contra entrega',
    ];

    protected $table = 'pedidos';

    protected $fillable = [
        'user_id',
        'codigo',
        'nombre',
        'email',
        'telefono',
        'direccion',
        'ciudad',
        'codigo_postal',
        'notas',
        'metodo_pago',
        'subtotal',
        'envio',
        'total',
        'estado',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'envio' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function getRouteKeyName(): string
    {
        return 'codigo';
    }

    public static function generarCodigo(): string
    {
        do {
            $codigo = 'HEL-'.strtoupper(Str::random(8));
        } while (static::where('codigo', $codigo)->exists());

        return $codigo;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function estadoTexto(): string
    {
        return self::ESTADOS[$this->estado] ?? $this->estado;
    }

    public function metodoPagoTexto(): string
    {
        return self::METODOS_PAGO[$this->metodo_pago] ?? $this->metodo_pago;
    }
}
