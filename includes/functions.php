<?php
/**
 * Database functions for page and post management
 */

/**
 * Get a page by its slug
 * @param mysqli $mysqli Database connection
 * @param string $slug Page slug
 * @return array|null Page data or null if not found
 */
function getPageBySlug($mysqli, $slug) {
    // TODO: Implement database logic to fetch page by slug
    // This is a stub for the user to implement their database connection
    $stmt = $mysqli->prepare("SELECT * FROM pages WHERE slug = ?");
    $stmt->bind_param('s', $slug);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

/**
 * Get all blog posts
 * @param mysqli $mysqli Database connection
 * @return array Array of blog posts
 */
function getAllPosts($mysqli) {
    // TODO: Implement database logic to fetch all posts
    $result = $mysqli->query("SELECT * FROM posts ORDER BY created_at DESC");
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

/**
 * Get all pages for menu
 * @param mysqli $mysqli Database connection
 * @return array Array of pages for menu
 */
function getMenuPages($mysqli) {
    // TODO: Implement database logic to fetch menu pages
    $result = $mysqli->query("SELECT slug, title FROM pages ORDER BY id ASC");
    $pages = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $pages[] = $row;
        }
    }
    return $pages;
}