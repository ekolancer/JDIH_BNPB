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
 * @property string $judul
 * @property string $slug
 * @property string $konten
 * @property string|null $penulis
 * @property string|null $kategori
 * @property string|null $gambar
 * @property bool $status
 * @property array<array-key, mixed>|null $tag
 * @property int $view_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $image_url
 * @property-read mixed $judul_short
 * @property-read mixed $konten_sanitized
 * @property-read mixed $penulis_label
 * @property-read mixed $tanggal_formatted
 * @property-read mixed $tanggal_short
 * @property-read mixed $view_count_formatted
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita whereGambar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita whereKonten($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita wherePenulis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita whereTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita whereViewCount($value)
 */
	class Berita extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $document_uid
 * @property string|null $tipeDok
 * @property string|null $jenisDok
 * @property int|null $ref_jenis_dokumen_id
 * @property string $judul
 * @property string $slug
 * @property string $teuBadan
 * @property int|null $nomor
 * @property int $tahun
 * @property string $singkatan
 * @property string $tempat
 * @property string $penerbit
 * @property string|null $tglPenetapan
 * @property string|null $tglPengundangan
 * @property string|null $tglDiBacakan
 * @property int|null $nomorPanggil
 * @property string $cetakanEdisi
 * @property string $deskripsiFisik
 * @property string|null $subjek
 * @property string $sumber
 * @property string $isbn
 * @property string|null $status
 * @property string $bahasa
 * @property string $lokasi
 * @property int|null $ref_bidang_hukum_id
 * @property string|null $bidangHukum
 * @property string $lampiran
 * @property string|null $abstrak
 * @property int|null $nomorIndukBuku
 * @property string $penandatangan
 * @property int $view_count
 * @property int $download_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RefBidangHukum|null $bidangHukumRef
 * @property-read array $abstrak_size
 * @property-read string $bidang_hukum_label
 * @property-read string $button_text
 * @property-read string $cover_image_url
 * @property-read string $created_at_formatted
 * @property-read bool $download_allowed
 * @property-read string|null $file_extension
 * @property-read bool $file_tersedia
 * @property-read bool $is_artikel
 * @property-read bool $is_image
 * @property-read bool $is_monografi
 * @property-read bool $is_pdf
 * @property-read string $jenis_dokumen_label
 * @property-read string $jenis_dokumen_singkatan
 * @property-read bool $lampiran_exists
 * @property-read array $lampiran_size
 * @property-read mixed $metadata
 * @property-read string|null $pdf_view_url
 * @property-read mixed $tgl_penetapan_formatted
 * @property-read mixed $tgl_pengundangan_formatted
 * @property-read mixed $tipe_dok_label
 * @property-read \App\Models\RefJenisDokumen|null $jenisDokumen
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RefSubjek> $subjekRef
 * @property-read int|null $subjek_ref_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum applyFilters(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum applySorting(string $sortBy)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum findBySlug($tipeDok, $slug)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum groupByColumns()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum latestPeraturan(int $limit = 4)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum mostViewedPeraturan(int $limit = 8)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereAbstrak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereBahasa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereBidangHukum($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereCetakanEdisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereDeskripsiFisik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereDocumentUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereDownloadCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereIsbn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereJenisDok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereLampiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereNomorIndukBuku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereNomorPanggil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum wherePenandatangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum wherePenerbit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereRefBidangHukumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereRefJenisDokumenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereSingkatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereSubjek($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereSumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereTempat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereTeuBadan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereTglDiBacakan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereTglPenetapan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereTglPengundangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereTipeDok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHukum whereViewCount($value)
 */
	class DokumenHukum extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $question
 * @property string|null $answer
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereUpdatedAt($value)
 */
	class Faq extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $judul
 * @property string $slug
 * @property array<array-key, mixed>|null $tag
 * @property string $gambar
 * @property bool $status
 * @property int $view_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $image_alt
 * @property-read mixed $image_url
 * @property-read mixed $judul_label
 * @property-read mixed $tanggal_formatted
 * @property-read mixed $view_count_formatted
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Infografis newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Infografis newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Infografis query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Infografis whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Infografis whereGambar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Infografis whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Infografis whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Infografis whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Infografis whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Infografis whereTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Infografis whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Infografis whereViewCount($value)
 */
	class Infografis extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string|null $content
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilPage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilPage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilPage whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilPage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilPage whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilPage whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilPage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfilPage whereUpdatedAt($value)
 */
	class ProfilPage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property string|null $deskripsi
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DokumenHukum> $dokumenHukum
 * @property-read int|null $dokumen_hukum_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefBidangHukum newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefBidangHukum newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefBidangHukum query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefBidangHukum whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefBidangHukum whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefBidangHukum whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefBidangHukum whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefBidangHukum whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefBidangHukum whereUpdatedAt($value)
 */
	class RefBidangHukum extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property string $singkatan
 * @property string|null $deskripsi
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DokumenHukum> $dokumenHukum
 * @property-read int|null $dokumen_hukum_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefJenisDokumen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefJenisDokumen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefJenisDokumen query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefJenisDokumen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefJenisDokumen whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefJenisDokumen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefJenisDokumen whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefJenisDokumen whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefJenisDokumen whereSingkatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefJenisDokumen whereUpdatedAt($value)
 */
	class RefJenisDokumen extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property string|null $kategori
 * @property string|null $deskripsi
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DokumenHukum> $dokumenHukum
 * @property-read int|null $dokumen_hukum_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefSubjek newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefSubjek newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefSubjek query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefSubjek whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefSubjek whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefSubjek whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefSubjek whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefSubjek whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefSubjek whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefSubjek whereUpdatedAt($value)
 */
	class RefSubjek extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $rating
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survey newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survey newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survey query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survey whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survey whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survey whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Survey whereUpdatedAt($value)
 */
	class Survey extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent implements \Filament\Models\Contracts\FilamentUser {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $ip_address
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon $visited_at
 * @property string|null $path
 * @property string|null $referrer
 * @property bool $suspicious
 * @property string|null $country
 * @property string|null $city
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereReferrer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereSuspicious($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereVisitedAt($value)
 */
	class Visitor extends \Eloquent {}
}

