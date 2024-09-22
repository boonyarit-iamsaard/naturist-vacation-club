import { SendPasswordResetLinkForm } from '~/app/(public)/reset-password/_components/send-password-reset-link-form';

export default function ForgotPasswordPage() {
  return (
    <section className="container py-8">
      <SendPasswordResetLinkForm />
    </section>
  );
}
