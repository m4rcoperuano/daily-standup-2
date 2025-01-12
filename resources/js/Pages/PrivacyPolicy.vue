<script setup>
  import { Head } from '@inertiajs/vue3';
  import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';

  defineProps( {
    policy: String,
  } );
</script>

<template>
  <Head title="Privacy Policy"></Head>

  <div class="font-sans pt-12 text-gray-100 antialiased">
    <div class="pt-4 pb-12">
      <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
        <div>
          <AuthenticationCardLogo></AuthenticationCardLogo>
        </div>

        <div class="prose prose-invert pt-12">
          <p><strong>Effective Date:</strong> 01/12/2025</p>

          <p>Sunny Orlando Devs, LLC (“we,” “us,” or “our”) is committed to protecting your privacy. This Privacy Policy outlines how we collect, use, and protect your personal information when you use Stellar Stand Ups (“the App”).</p>

          <hr />

          <h2>1. Information We Collect</h2>
          <h3>1.1 User-Provided Information</h3>
          <p>When you use the App, you may provide the following information:</p>
          <ul>
            <li><strong>Account Information:</strong> Such as your name, email address, and other details required to create and manage your account.</li>
            <li><strong>App Usage Data:</strong> Information you input into the App, such as daily logs, sprint activities, and pointing session details.</li>
          </ul>

          <h3>1.2 Automatically Collected Information</h3>
          <p>The App may collect limited technical information, including:</p>
          <ul>
            <li>Device information (e.g., operating system, device type).</li>
            <li>Usage statistics for troubleshooting and analytics.</li>
          </ul>

          <p>We do not collect sensitive personal information such as financial details, social security numbers, or other identifiers.</p>

          <hr />

          <h2>2. How We Use Your Information</h2>
          <p>We use the information collected to:</p>
          <ul>
            <li>Provide, maintain, and improve the App’s functionality.</li>
            <li>Communicate updates, feature announcements, or promotional content related to the App.</li>
            <li>Ensure compliance with our Terms and Conditions.</li>
          </ul>
          <p>We do not use the data you enter into the App (e.g., daily logs or pointing session details) for training AI models, commercial purposes, or any other purpose beyond the App’s intended functionality.</p>

          <hr />

          <h2>3. Email Communications</h2>
          <h3>3.1 Marketing Emails</h3>
          <p>We may use your email address to send you marketing materials or notify you of new features and updates. You can opt out of receiving these emails by:</p>
          <ul>
            <li>Following the unsubscribe link in the email.</li>
            <li>Contacting us directly at support@sunnyorlando.dev.</li>
          </ul>

          <h3>3.2 Administrative Emails</h3>
          <p>We may also send emails related to account management or critical updates. These communications are not optional.</p>

          <hr />

          <h2>4. Data Sharing and Disclosure</h2>
          <p>We do not sell, rent, or share your personal information with third parties, except as necessary to:</p>
          <ul>
            <li>Comply with legal obligations or respond to lawful requests.</li>
            <li>Protect the rights and safety of our users and Sunny Orlando Devs, LLC.</li>
            <li>Collaborate with service providers that assist in delivering the App’s functionality (e.g., hosting providers), subject to strict confidentiality obligations.</li>
          </ul>

          <hr />

          <h2>5. Data Retention</h2>
          <p>We retain your information only as long as necessary to fulfill the purposes outlined in this Privacy Policy or as required by law. You may request the deletion of your account and associated data by contacting us.</p>

          <hr />

          <h2>6. Data Security</h2>
          <p>We implement industry-standard security measures to protect your information from unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the internet or electronic storage is completely secure, and we cannot guarantee absolute security.</p>

          <hr />

          <h2>7. Children’s Privacy</h2>
          <p>The App is not intended for use by children under the age of 13. We do not knowingly collect personal information from children. If we become aware of such collection, we will delete the information promptly.</p>

          <hr />

          <h2>8. Your Rights</h2>
          <p>Depending on your location, you may have rights under applicable data protection laws, including the right to:</p>
          <ul>
            <li>Access, correct, or delete your personal information.</li>
            <li>Object to or restrict the processing of your data.</li>
            <li>Withdraw consent where processing is based on consent.</li>
          </ul>
          <p>To exercise these rights, please contact us at support@sunnyorlando.dev.</p>

          <hr />

          <h2>9. Changes to this Privacy Policy</h2>
          <p>We may update this Privacy Policy from time to time. Any changes will be effective immediately upon posting the updated Privacy Policy. We encourage you to review this policy periodically.</p>

          <hr />

          <h2>10. Contact Us</h2>
          <p>If you have any questions or concerns about this Privacy Policy, please contact us:</p>

          <address>
            <strong>Sunny Orlando Devs, LLC</strong><br />
            Email: support@sunnyorlando.dev
          </address>

          <p>By using Stellar Stand Ups, you acknowledge that you have read and understood this Privacy Policy and agree to its terms.</p>
        </div>
      </div>
    </div>
  </div>
</template>
