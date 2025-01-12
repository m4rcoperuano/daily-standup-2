<script setup>
  import { Head } from '@inertiajs/vue3';
  import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';

  defineProps( {
    terms: String,
  } );
</script>

<template>
  <Head title="Terms of Service"></Head>

  <div class="font-sans text-gray-100 antialiased pt-12">
    <div class="pt-4">
      <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
        <div class="mb-8">
          <AuthenticationCardLogo></AuthenticationCardLogo>
        </div>

        <div class="prose prose-invert pb-12">
          <h2>Terms and Conditions</h2>
          <h3>Stellar Stand Ups</h3>

          <p>Effective Date: 01/12/2025</p>

          <p>Welcome to Stellar Stand Ups, a software application developed and owned by Sunny Orlando Devs, LLC (referred to as “we,” “us,” or “our”). By accessing or using Stellar Stand Ups (“the App”), you agree to be bound by these Terms and Conditions (“Terms”). Please read them carefully.</p>

          <h4>1. Acceptance of Terms</h4>

          <p>By using the App, you agree to comply with and be bound by these Terms. If you do not agree with any part of these Terms, you must not use the App.</p>

          <h4>2. Purpose of the App</h4>

          <p>Stellar Stand Ups is designed as a utility tool for developers to log daily tasks and activities during sprints and facilitate pointing sessions. The App aims to simplify and enhance the workflow of development teams.</p>

          <h4>3. User Data</h4>

          <h4>3.1 Data Entry</h4>

          <p>The data you input into the App (e.g., daily logs, sprint activities, or pointing session details) is used solely for your and your team’s convenience. We do not use this data for:</p>

          <p>- Training AI models.</p>

          <p>- Any commercial or non-commercial purposes outside the scope of the App’s functionality.</p>

          <h4>3.2 Email Usage</h4>

          <p>The email address you provide may be used to:</p>

          <p> - Communicate updates about the App.</p>

          <p> - Share promotional material or marketing content related to new features or services.</p>

          <p> - You may opt out of receiving marketing emails by following the instructions in the email or contacting us directly.</p>

          <h4>4. Privacy Policy</h4>

          <p>Our Privacy Policy, available <a href="/privacy">here</a>, provides detailed information on how we collect, use, and protect your information. By using the App, you consent to the practices described in our Privacy Policy.</p>

          <h4>5. License and Use</h4>

          <p>We grant you a limited, non-exclusive, non-transferable, and revocable license to use the App for its intended purpose. You agree not to:</p>

          <p>- Reverse-engineer, modify, or create derivative works based on the App.</p>

          <p>- Use the App for any unlawful or unauthorized purpose.</p>

          <h4>6. Intellectual Property</h4>

          <p>The App, including its design, features, and underlying code, is the intellectual property of Sunny Orlando Devs, LLC. You may not reproduce, distribute, or exploit any part of the App without our express written consent.</p>

          <h4>7. Disclaimer of Warranties</h4>

          <p>The App is provided on an “AS IS” and “AS AVAILABLE” basis. We make no warranties, express or implied, about the App’s reliability, functionality, or suitability for your specific needs. Your use of the App is at your own risk.</p>

          <h4>8. Limitation of Liability</h4>

          <p>To the fullest extent permitted by law, Sunny Orlando Devs, LLC shall not be liable for any indirect, incidental, or consequential damages resulting from your use of the App.</p>

          <h4>9. Modifications to Terms</h4>

          <p>We may update these Terms from time to time. Any changes will be effective immediately upon posting the updated Terms. It is your responsibility to review these Terms periodically for updates.</p>

          <h4>10. Termination</h4>

          <p>We reserve the right to terminate or suspend your access to the App at our discretion, without prior notice, if you violate these Terms.</p>

          <h4>11. Governing Law</h4>

          <p>These Terms are governed by the laws of the State of Florida, without regard to its conflict of laws principles. Any disputes arising from these Terms shall be resolved exclusively in the state or federal courts located in Florida.</p>

          <h4>12. Contact Us</h4>

          <p>If you have any questions about these Terms or the App, please contact us at:</p>

          <p>Sunny Orlando Devs, LLC Email: support@sunnyorlando.dev</p>

          <p>By using Stellar Stand Ups, you acknowledge that you have read, understood, and agree to these Terms and Conditions.</p>
        </div>
      </div>
    </div>
  </div>
</template>
