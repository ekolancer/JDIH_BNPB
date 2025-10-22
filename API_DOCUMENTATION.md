# JDIH BNPB API Documentation

## Overview
The JDIH BNPB API provides access to legal documents (Dokumen Hukum) from the National Disaster Management Agency (BNPB) of Indonesia. This API allows developers to retrieve, filter, and search legal documents programmatically.

## Base URL
```
https://jdih.bnpb.go.id/api
```

## Authentication
Currently, this API does not require authentication. However, rate limiting is enforced to prevent abuse.

## Rate Limiting
- **60 requests per minute** per IP address
- Exceeding this limit will result in a 429 (Too Many Requests) response

## Endpoints

### GET /api
Retrieve a paginated list of legal documents with optional filtering and sorting.

#### Parameters

| Parameter | Type | Required | Description | Example |
|-----------|------|----------|-------------|---------|
| `per_page` | integer | No | Number of items per page (1-100, default: 15) | `per_page=25` |
| `page` | integer | No | Page number (minimum: 1) | `page=2` |
| `tipeDok` | string | No | Document type filter: `Peraturan`, `Monografi`, `Artikel`, `Putusan` | `tipeDok=Peraturan` |
| `tahun` | integer | No | Publication year filter (1900 to current year + 1) | `tahun=2023` |
| `judul` | string | No | Title search (partial match, max 255 chars) | `judul=undang-undang` |
| `subjek` | string | No | Subject search (partial match, max 255 chars) | `subjek=bencana` |
| `bidangHukum` | string | No | Legal field filter (max 255 chars) | `bidangHukum=Hukum Pidana` |
| `sort_by` | string | No | Sort field: `id`, `tahun`, `judul`, `created_at` (default: `created_at`) | `sort_by=tahun` |
| `sort_order` | string | No | Sort order: `asc`, `desc` (default: `desc`) | `sort_order=asc` |

#### Example Requests

1. **Get all documents (default pagination)**
   ```
   GET /api
   ```

2. **Get regulations only, 10 per page**
   ```
   GET /api?tipeDok=Peraturan&per_page=10
   ```

3. **Search documents by title**
   ```
   GET /api?judul=undang-undang&page=1&per_page=20
   ```

4. **Filter by year and sort by title**
   ```
   GET /api?tahun=2023&sort_by=judul&sort_order=asc
   ```

5. **Advanced search with multiple filters**
   ```
   GET /api?tipeDok=Peraturan&tahun=2023&subjek=bencana&bidangHukum=Hukum Pidana&per_page=50
   ```

#### Response Format

##### Success Response (200 OK)
```json
{
  "data": [
    {
      "idData": 1,
      "tahun_pengundangan": 2023,
      "tanggal_pengundangan": "2023-12-01",
      "jenis": "Undang-Undang",
      "noPeraturan": 15,
      "judul": "Undang-Undang tentang Penanggulangan Bencana",
      "noPanggil": null,
      "singkatanJenis": "UU",
      "tempatTerbit": "Jakarta",
      "penerbit": "Pemerintah Republik Indonesia",
      "deskripsiFisik": "-",
      "sumber": "Lembaran Negara Republik Indonesia Tahun 2023 Nomor 200",
      "subjek": "Penanggulangan Bencana",
      "isbn": "-",
      "status": "BERLAKU",
      "bahasa": "Indonesia",
      "bidangHukum": "Hukum Tata Negara",
      "teuBadan": "Presiden Republik Indonesia",
      "nomorIndukBuku": null,
      "fileDownload": "uu_no_15_tahun_2023.pdf",
      "urlDownload": "https://jdih.bnpb.go.id/download-lampiran/uu-no-15-tahun-2023",
      "abstrak": "Undang-undang ini mengatur tentang penanggulangan bencana di Indonesia...",
      "urlabstrak": "https://jdih.bnpb.go.id/download-abstrak/uu-no-15-tahun-2023",
      "urlDetailPeraturan": "https://jdih.bnpb.go.id/dokumen/Peraturan/uu-no-15-tahun-2023",
      "operasi": "4",
      "display": "1"
    }
  ],
  "links": {
    "first": "https://jdih.bnpb.go.id/api?page=1",
    "last": "https://jdih.bnpb.go.id/api?page=10",
    "prev": null,
    "next": "https://jdih.bnpb.go.id/api?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 10,
    "links": [
      {
        "url": null,
        "label": "&laquo; Previous",
        "active": false
      },
      {
        "url": "https://jdih.bnpb.go.id/api?page=1",
        "label": "1",
        "active": true
      },
      {
        "url": "https://jdih.bnpb.go.id/api?page=2",
        "label": "2",
        "active": false
      },
      {
        "url": null,
        "label": "...",
        "active": false
      },
      {
        "url": "https://jdih.bnpb.go.id/api?page=10",
        "label": "10",
        "active": false
      },
      {
        "url": "https://jdih.bnpb.go.id/api?page=2",
        "label": "Next &raquo;",
        "active": false
      }
    ],
    "path": "https://jdih.bnpb.go.id/api",
    "per_page": 15,
    "to": 15,
    "total": 150
  }
}
```

##### Error Responses

**Validation Error (422 Unprocessable Entity)**
```json
{
  "error": "Validation failed",
  "message": "Invalid request parameters",
  "details": {
    "per_page": ["The per_page must be an integer."],
    "tipeDok": ["The selected tipeDok is invalid."]
  }
}
```

**Server Error (500 Internal Server Error)**
```json
{
  "error": "Internal server error",
  "message": "An unexpected error occurred. Please try again later."
}
```

**Rate Limit Exceeded (429 Too Many Requests)**
```json
{
  "message": "API rate limit exceeded.",
  "retry_after": 60
}
```

## Data Fields Description

| Field | Type | Description |
|-------|------|-------------|
| `idData` | integer | Unique document ID |
| `tahun_pengundangan` | integer | Publication year |
| `tanggal_pengundangan` | date | Publication date |
| `jenis` | string | Document type |
| `noPeraturan` | integer/null | Regulation number |
| `judul` | string | Document title |
| `noPanggil` | integer/null | Call number (for monographs) |
| `singkatanJenis` | string | Type abbreviation |
| `tempatTerbit` | string | Publication place |
| `penerbit` | string | Publisher |
| `deskripsiFisik` | string | Physical description |
| `sumber` | string | Source |
| `subjek` | string | Subject |
| `isbn` | string | ISBN (for monographs) |
| `status` | string | Status (BERLAKU/TIDAK BERLAKU) |
| `bahasa` | string | Language |
| `bidangHukum` | string | Legal field |
| `teuBadan` | string | Authoring body |
| `nomorIndukBuku` | integer/null | Book index number |
| `fileDownload` | string | Download filename |
| `urlDownload` | string/null | Download URL |
| `abstrak` | string/null | Abstract content |
| `urlabstrak` | string/null | Abstract download URL |
| `urlDetailPeraturan` | string | Detail page URL |
| `operasi` | string | Operation code (fixed: "4") |
| `display` | string | Display code (fixed: "1") |

## Usage Examples

### JavaScript (Fetch API)
```javascript
fetch('https://jdih.bnpb.go.id/api?tipeDok=Peraturan&per_page=10')
  .then(response => response.json())
  .then(data => {
    console.log('Total documents:', data.meta.total);
    console.log('Current page:', data.meta.current_page);
    data.data.forEach(doc => {
      console.log(doc.judul, doc.tahun_pengundangan);
    });
  })
  .catch(error => console.error('Error:', error));
```

### PHP (cURL)
```php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://jdih.bnpb.go.id/api?tahun=2023');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
foreach ($data['data'] as $document) {
    echo $document['judul'] . ' (' . $document['tahun_pengundangan'] . ')' . PHP_EOL;
}
```

### Python (requests)
```python
import requests

response = requests.get('https://jdih.bnpb.go.id/api', params={
    'tipeDok': 'Peraturan',
    'tahun': 2023,
    'per_page': 20
})

if response.status_code == 200:
    data = response.json()
    for doc in data['data']:
        print(f"{doc['judul']} - {doc['tahun_pengundangan']}")
else:
    print(f"Error: {response.status_code}")
```

## Notes
- All text searches are case-insensitive partial matches
- Dates are returned in YYYY-MM-DD format
- URLs are absolute and include the base domain
- The API supports CORS for cross-origin requests
- Response times may vary based on filter complexity and data volume

## Support
For technical support or questions about this API, please contact the JDIH BNPB development team.
