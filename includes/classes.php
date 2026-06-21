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
    // Edit/update can be added later
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
    // Edit/update can be added later
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
    // Edit/update can be added later
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