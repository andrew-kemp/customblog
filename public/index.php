<?php
$pageTitle = "Welcome to My Blog";
$currentPage = '';

// Handle basic routing for different pages
$page = $_GET['page'] ?? '';

switch ($page) {
    case 'about':
        $pageTitle = "About";
        $currentPage = 'about';
        break;
    case 'contact':
        $pageTitle = "Contact";
        $currentPage = 'contact';
        break;
    case 'blog':
        $pageTitle = "Blog";
        $currentPage = 'blog';
        break;
    default:
        $pageTitle = "Welcome to My Blog";
        $currentPage = '';
        break;
}

include __DIR__ . '/../includes/header.php';
?>

<?php if ($page === 'about'): ?>
    <h1>About Us</h1>
    <p>Welcome to our blog! This is a modern custom blog built with PHP. We share insights about technology, programming, and more.</p>
    <p>Feel free to browse our posts and get in touch if you have any questions or feedback.</p>

<?php elseif ($page === 'contact'): ?>
    <h1>Contact Us</h1>
    <p>We'd love to hear from you! Get in touch using the information below:</p>
    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin: 1rem 0;">
        <p><strong>Email:</strong> contact@myblog.com</p>
        <p><strong>Phone:</strong> (555) 123-4567</p>
        <p><strong>Address:</strong> 123 Blog Street, Web City, Internet 12345</p>
    </div>

<?php elseif ($page === 'blog'): ?>
    <h1>Blog Posts</h1>
    <div style="border: 1px solid #ddd; border-radius: 8px; padding: 1.5rem; margin: 1rem 0;">
        <h2>Welcome to Our Blog!</h2>
        <p style="color: #666; margin-bottom: 1rem;"><em>Posted on <?= date('Y-m-d') ?></em></p>
        <p>This is your first blog post. Welcome to your new custom blog! You can create, edit, and manage posts through the admin panel.</p>
        <p>This blog supports modern features and has a clean, responsive design that works great on all devices.</p>
    </div>
    <div style="text-align: center; margin: 2rem 0;">
        <p style="color: #888;">More posts coming soon! <a href="/admin/" style="color: #232946;">Admin Login</a></p>
    </div>

<?php else: ?>
    <h1>Welcome to My Blog</h1>
    <img src="/assets/banner.jpg" alt="Blog Banner" style="width: 100%; max-height: 300px; object-fit: cover; border-radius: 8px; margin: 1rem 0;">
    <p>Welcome to our modern, custom blog! Here you'll find the latest posts about technology, programming, and web development.</p>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin: 2rem 0;">
        <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px;">
            <h3>📝 Latest Posts</h3>
            <p>Discover our newest articles and insights on technology and web development.</p>
            <a href="/?page=blog" style="color: #232946; text-decoration: none; font-weight: bold;">Read Blog →</a>
        </div>
        
        <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px;">
            <h3>👋 About Us</h3>
            <p>Learn more about our mission and the team behind this blog.</p>
            <a href="/?page=about" style="color: #232946; text-decoration: none; font-weight: bold;">Learn More →</a>
        </div>
        
        <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px;">
            <h3>📧 Get in Touch</h3>
            <p>Have questions or feedback? We'd love to hear from you!</p>
            <a href="/?page=contact" style="color: #232946; text-decoration: none; font-weight: bold;">Contact Us →</a>
        </div>
    </div>
    
    <div style="text-align: center; margin: 2rem 0; padding: 1.5rem; background: #e0e7ff; border-radius: 8px;">
        <p style="margin: 0;">Are you an admin? <a href="/login.php" style="color: #232946; text-decoration: none; font-weight: bold;">Login here</a> to manage your blog.</p>
    </div>
<?php endif; ?>

<?php
include __DIR__ . '/../includes/footer.php';
?>