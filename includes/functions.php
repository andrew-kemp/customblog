function get_site_title() {
    global $conn;
    $result = $conn->query("SELECT value FROM settings WHERE name='site_title' LIMIT 1");
    if ($result && $row = $result->fetch_assoc()) {
        return $row['value'];
    }
    return 'My Custom Blog';
}