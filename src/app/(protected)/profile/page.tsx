import { UpdatePasswordForm } from '~/app/(protected)/profile/_components/update-password-form';
import { UpdateProfileForm } from '~/app/(protected)/profile/_components/update-profile-form';

export default function Page() {
  // TODO: Add protected route

  return (
    <section className="container max-w-screen-lg items-center space-y-4 py-8">
      <h1 className="text-4xl font-bold tracking-tight">Profile</h1>
      <UpdateProfileForm />
      <UpdatePasswordForm />
    </section>
  );
}
