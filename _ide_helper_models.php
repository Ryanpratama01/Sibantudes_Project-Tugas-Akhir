<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $rt_id
 * @property string $no_kk
 * @property string $nik
 * @property string $nama_lengkap
 * @property string $jenis_kelamin
 * @property string $tempat_lahir
 * @property \Illuminate\Support\Carbon $tanggal_lahir
 * @property string $alamat
 * @property string $desa
 * @property string $pekerjaan
 * @property numeric $penghasilan
 * @property int $jumlah_tanggungan
 * @property string $aset_kepemilikan
 * @property string $bantuan_lain
 * @property int $usia
 * @property string $status_perkawinan
 * @property string $status_verifikasi
 * @property string|null $catatan_admin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PenerimaFinal|null $penerimaFinal
 * @property-read \App\Models\PrediksiKelayakan|null $prediksiKelayakan
 * @property-read \App\Models\RT $rt
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima whereAsetKepemilikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima whereBantuanLain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima whereCatatanAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima whereDesa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima whereJumlahTanggungan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima whereNamaLengkap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima whereNoKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima wherePekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima wherePenghasilan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima whereRtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima whereStatusPerkawinan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima whereStatusVerifikasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalonPenerima whereUsia($value)
 */
	class CalonPenerima extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama_dusun
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RT> $rts
 * @property-read int|null $rts_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dusun newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dusun newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dusun query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dusun whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dusun whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dusun whereNamaDusun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dusun whereUpdatedAt($value)
 */
	class Dusun extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $calon_penerima_id
 * @property \Illuminate\Support\Carbon $tanggal_penetapan
 * @property string $periode_bantuan
 * @property numeric $jumlah_bantuan
 * @property string $status_pencairan
 * @property \Illuminate\Support\Carbon|null $tanggal_pencairan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CalonPenerima $calonPenerima
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenerimaFinal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenerimaFinal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenerimaFinal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenerimaFinal whereCalonPenerimaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenerimaFinal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenerimaFinal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenerimaFinal whereJumlahBantuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenerimaFinal wherePeriodeBantuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenerimaFinal whereStatusPencairan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenerimaFinal whereTanggalPencairan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenerimaFinal whereTanggalPenetapan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenerimaFinal whereUpdatedAt($value)
 */
	class PenerimaFinal extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $calon_penerima_id
 * @property numeric $probability
 * @property string $recommendation
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CalonPenerima $calonPenerima
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrediksiKelayakan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrediksiKelayakan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrediksiKelayakan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrediksiKelayakan whereCalonPenerimaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrediksiKelayakan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrediksiKelayakan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrediksiKelayakan whereProbability($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrediksiKelayakan whereRecommendation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrediksiKelayakan whereUpdatedAt($value)
 */
	class PrediksiKelayakan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CalonPenerima> $calonPenerimas
 * @property-read int|null $calon_penerimas_count
 * @property-read \App\Models\Dusun|null $dusun
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RT newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RT newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RT query()
 */
	class RT extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $rt_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CalonPenerima> $calonPenerimas
 * @property-read int|null $calon_penerimas_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\RT|null $rt
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

