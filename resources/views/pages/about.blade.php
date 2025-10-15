@extends('pages.layout')

@section('title', 'About Us')
@section('subtitle', 'Learn more about Review Pro and our mission')

@section('content')
<div class="content">
    <h2>Who We Are</h2>
    <p>
        Review Pro is a leading Amazon review management platform designed to help businesses streamline their review campaigns,
        manage multiple accounts efficiently, and boost their Amazon presence with powerful automation tools.
    </p>

    <p>
        Founded by a team of e-commerce experts and software engineers, Review Pro was created to solve the common challenges
        faced by Amazon sellers when managing customer reviews at scale.
    </p>

    <h2>Our Mission</h2>
    <p>
        Our mission is to empower businesses of all sizes with the tools they need to effectively manage their Amazon review
        campaigns, save time through automation, and make data-driven decisions to improve their products and customer satisfaction.
    </p>

    <div class="info-box">
        <h4><i class="mdi mdi-lightbulb-on"></i> Why Choose Review Pro?</h4>
        <p>
            We understand the importance of customer reviews in building trust and driving sales on Amazon. Our platform
            combines advanced automation with powerful analytics to help you manage reviews efficiently while staying
            compliant with Amazon's policies.
        </p>
    </div>

    <h2>Our Values</h2>
    <ul>
        <li><strong>Innovation:</strong> We continuously improve our platform with cutting-edge features and technologies</li>
        <li><strong>Reliability:</strong> 99.9% uptime ensures your campaigns run smoothly without interruption</li>
        <li><strong>Security:</strong> Bank-level encryption protects your sensitive account information</li>
        <li><strong>Support:</strong> 24/7 customer support to help you succeed</li>
        <li><strong>Transparency:</strong> Clear pricing with no hidden fees</li>
    </ul>

    <h2>What We Offer</h2>
    <p>Review Pro provides a comprehensive suite of tools designed specifically for Amazon sellers:</p>

    <ul>
        <li>Smart automation for review management</li>
        <li>Multi-account management from a single dashboard</li>
        <li>Real-time analytics and performance tracking</li>
        <li>Secure data storage and encryption</li>
        <li>Project organization and workflow management</li>
        <li>Time-saving automation features</li>
    </ul>

    <h2>Get Started Today</h2>
    <p>
        Join hundreds of businesses already using Review Pro to transform their Amazon review management.
        Contact us to learn more about how we can help your business grow.
    </p>

    <div class="text-center mt-5">
        <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '') }}" class="btn btn-back" style="background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white !important; border: none;">
            <i class="mdi mdi-whatsapp"></i> Contact Us on WhatsApp
        </a>
    </div>
</div>
@endsection
