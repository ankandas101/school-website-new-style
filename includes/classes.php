<?php
// School Info class
if (!class_exists('SchoolInfo')) {
class SchoolInfo {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }
    public function get() {
        $sql = 'SELECT * FROM school_info WHERE id=1';
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }
}
}
// Footer Info class 
if (!class_exists('FooterInfo')) {
class FooterInfo {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }
    public function get() {
        $sql = 'SELECT * FROM footer_info WHERE id=1';
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }
}
}

// Slider class
if (!class_exists('Slider')) {
class Slider {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }
    public function getActive() {
        $sql = 'SELECT * FROM sliders WHERE status=1 ORDER BY sort_order ASC, id DESC';
        return $this->conn->query($sql);
    }
}
}

// Message class
if (!class_exists('Message')) {
class Message {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }
    public function get($type) {
        $stmt = $this->conn->prepare('SELECT * FROM messages WHERE type=?');
        $stmt->bind_param('s', $type);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
}

// Notice class
if (!class_exists('Notice')) {
class Notice {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }
    public function getActive($limit = 5, $forTicker = false) {
        $sql = 'SELECT * FROM notices WHERE status=1';
        if ($forTicker) {
            $sql .= ' AND show_in_ticker=1';
        }
        $sql .= ' ORDER BY notice_date IS NULL, notice_date DESC, id DESC LIMIT ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        return $stmt->get_result();
    }
}
}

// SidebarWidget class
if (!class_exists('SidebarWidget')) {
class SidebarWidget {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }
    public function getActive() {
        $sql = "SELECT * FROM sidebar_widgets WHERE status=1 ORDER BY sort_order ASC, id DESC";
        return $this->conn->query($sql);
    }
}
}

if (!class_exists('ManagementCommittee')) {
class ManagementCommittee {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }
    public function getAll() {
        $sql = 'SELECT * FROM management_committee ORDER BY sort_order ASC, id DESC';
        return $this->conn->query($sql);
    }
    public function add($full_name, $title, $image, $contact_number, $joining_date) {
        $stmt = $this->conn->prepare('INSERT INTO management_committee (full_name, title, image, contact_number, joining_date, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())');
        $stmt->bind_param('sssss', $full_name, $title, $image, $contact_number, $joining_date);
        return $stmt->execute();
    }
    public function delete($id) {
        $stmt = $this->conn->prepare('DELETE FROM management_committee WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
}

// ImportantLinks class
if (!class_exists('ImportantLinks')) {
class ImportantLinks {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }
    public function getActive() {
        $sql = "SELECT * FROM important_links WHERE status=1 ORDER BY sort_order ASC, id DESC";
        return $this->conn->query($sql);
    }
    public function getAll() {
        $sql = "SELECT * FROM important_links ORDER BY sort_order ASC, id DESC";
        return $this->conn->query($sql);
    }
    public function add($title, $url, $sort_order, $status) {
        $stmt = $this->conn->prepare('INSERT INTO important_links (title, url, sort_order, status) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssii', $title, $url, $sort_order, $status);
        return $stmt->execute();
    }
    public function delete($id) {
        $stmt = $this->conn->prepare('DELETE FROM important_links WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
}

// AcademicInfoLinks class
if (!class_exists('AcademicInfoLinks')) {
class AcademicInfoLinks {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }
    public function getActive() {
        $sql = "SELECT * FROM academic_info_links WHERE status=1 ORDER BY sort_order ASC, id DESC";
        return $this->conn->query($sql);
    }
    public function getAll() {
        $sql = "SELECT * FROM academic_info_links ORDER BY sort_order ASC, id DESC";
        return $this->conn->query($sql);
    }
    public function add($title, $url, $sort_order, $status) {
        $stmt = $this->conn->prepare('INSERT INTO academic_info_links (title, url, sort_order, status) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssii', $title, $url, $sort_order, $status);
        return $stmt->execute();
    }
    public function delete($id) {
        $stmt = $this->conn->prepare('DELETE FROM academic_info_links WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
}

// SchoolStatistics class
if (!class_exists('SchoolStatistics')) {
class SchoolStatistics {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }
    public function getActive() {
        $sql = "SELECT * FROM school_statistics WHERE status=1 ORDER BY sort_order ASC, id DESC";
        return $this->conn->query($sql);
    }
    public function getAll() {
        $sql = "SELECT * FROM school_statistics ORDER BY sort_order ASC, id DESC";
        return $this->conn->query($sql);
    }
    public function add($title, $value, $suffix = null, $icon = null, $sort_order = 1, $status = 1) {
        $stmt = $this->conn->prepare('INSERT INTO school_statistics (title, value, suffix, icon, sort_order, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())');
        $stmt->bind_param('ssssii', $title, $value, $suffix, $icon, $sort_order, $status);
        return $stmt->execute();
    }
    public function update($id, $title, $value, $suffix = null, $icon = null, $sort_order = 1, $status = 1) {
        $stmt = $this->conn->prepare('UPDATE school_statistics SET title = ?, value = ?, suffix = ?, icon = ?, sort_order = ?, status = ?, updated_at = NOW() WHERE id = ?');
        $stmt->bind_param('ssssiii', $title, $value, $suffix, $icon, $sort_order, $status, $id);
        return $stmt->execute();
    }
    public function delete($id) {
        $stmt = $this->conn->prepare('DELETE FROM school_statistics WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
    public function getById($id) {
        $stmt = $this->conn->prepare('SELECT * FROM school_statistics WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
}



// Complaint class - handles all complaint-related DB operations
if (!class_exists('Complaint')) {
class Complaint {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Insert a new complaint record
     */
    public function insert($data) {
        $complaint_id = $this->generateComplaintId();

        $stmt = $this->conn->prepare(
            "INSERT INTO complaints (
                complaint_id, student_name, class_name, roll_number,
                phone, complaint_type, incident_date, complaint_details,
                attachment, anonymous, status, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, NOW())"
        );

        $stmt->bind_param(
            'sssssssssi',
            $complaint_id,
            $data['student_name'],
            $data['class_name'],
            $data['roll_number'],
            $data['phone'],
            $data['complaint_type'],
            $data['incident_date'],
            $data['complaint_details'],
            $data['attachment'],
            $data['anonymous']
        );

        if ($stmt->execute()) {
            return $complaint_id;
        }
        return false;
    }

    /**
     * Get all complaints with optional status filter, pagination
     */
    public function getAll($status = null, $limit = 10, $offset = 0) {
        $sql = "SELECT * FROM complaints";
        $params = [];
        $types = '';

        if ($status !== null) {
            $sql .= " WHERE status = ?";
            $params[] = $status;
            $types .= 'i';
        }

        $sql .= " ORDER BY created_at DESC, id DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';

        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * Get total count of complaints (with optional status filter)
     */
    public function getCount($status = null) {
        $sql = "SELECT COUNT(*) as total FROM complaints";
        $params = [];
        $types = '';

        if ($status !== null) {
            $sql .= " WHERE status = ?";
            $params[] = $status;
            $types .= 'i';
        }

        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    /**
     * Get a single complaint by ID
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM complaints WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * Update complaint status (0 = pending, 1 = reviewed, 2 = resolved, 3 = rejected)
     */
    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE complaints SET status = ? WHERE id = ?");
        $stmt->bind_param('ii', $status, $id);
        return $stmt->execute();
    }

    /**
     * Delete a complaint by ID
     */
    public function delete($id) {
        // First get the complaint to check for attachment
        $complaint = $this->getById($id);
        if ($complaint && !empty($complaint['attachment'])) {
            $file_path = __DIR__ . '/../uploads/complaints/' . $complaint['attachment'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        $stmt = $this->conn->prepare("DELETE FROM complaints WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    /**
     * Generate a unique complaint ID (CMP-00001 format)
     */
    private function generateComplaintId() {
        $res = $this->conn->query("SELECT id FROM complaints ORDER BY id DESC LIMIT 1");
        $next = 1;
        if ($res && $res->num_rows) {
            $row = $res->fetch_assoc();
            $next = $row['id'] + 1;
        }
        return 'CMP-' . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Validate and upload attachment file
     * Returns the filename on success, empty string on no file, or throws on error
     */
    public function uploadAttachment($file) {
        if (!$file || $file['error'] !== 0) {
            return '';
        }

        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            throw new Exception('শুধুমাত্র JPG, PNG অথবা PDF আপলোড করা যাবে।');
        }

        $upload_dir = __DIR__ . '/../uploads/complaints';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $filename = time() . '_' . uniqid() . '.' . $ext;
        $dest_path = $upload_dir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest_path)) {
            throw new Exception('ফাইল আপলোড ব্যর্থ হয়েছে।');
        }

        return $filename;
    }

    /**
     * Validate Cloudflare Turnstile captcha
     */
    public static function verifyTurnstile($turnstile_response, $secret_key) {
        if (empty($turnstile_response)) {
            return false;
        }

        $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
        $data = [
            'secret' => $secret_key,
            'response' => $turnstile_response,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);
        if ($result === false) {
            return false;
        }

        $response_data = json_decode($result, true);
        return isset($response_data['success']) && $response_data['success'] === true;
    }

    /**
     * Load Turnstile configuration from environment variables
     */
    public static function getTurnstileConfig() {
        return [
            'site_key' => env('TURNSTILE_SITE_KEY', ''),
            'secret_key' => env('TURNSTILE_SECRET_KEY', ''),
            'status' => env('TURNSTILE_STATUS', '0')
        ];
    }
}
}





$isSoftware = strtolower((string)env('SYSTEM_SOFTWARE', 'false')) === 'true';


// School License Verification
class license_info {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function get() {
        $sql = "SELECT * FROM license_info WHERE id=1";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }
}