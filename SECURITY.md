# Security Policy

Thank you for your interest in shiroiAdmin security.

If you discover a security vulnerability, please contact the project maintainers via email and provide as much detail as possible, including the vulnerability description, affected versions, reproduction steps, and a PoC if available. We will verify and release a fix promptly.

## Supported Versions

| Version    | Supported |
|------------|----------|
| v1.4+      | ✅       |
| <= v1.3    | ❌       |

---

# Security Advisory

## SA-2026-001

### Title

Unauthenticated File Upload Vulnerability (CWE-434)

### Severity

High

### Affected Versions

- <= v1.3

### Fixed Versions

- >= v1.4

### Vulnerability Description

The file upload endpoint (`app/common/controller/FileController.php`) contains a file type validation flaw.

The application relied on the client-supplied `file_type` parameter to determine which validation rules to apply:

```php
$file_type = $param['file_type'] ?? get_file_type($file->getOriginalName());
