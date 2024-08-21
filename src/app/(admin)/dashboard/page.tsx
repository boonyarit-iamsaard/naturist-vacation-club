import { redirect } from 'next/navigation';

import { isAdmin } from '~/libs/auth/validators/is-admin';

export default function Page() {
  if (!isAdmin()) {
    redirect('/');
  }

  return (
    <section className="container flex flex-col items-center gap-4 py-8">
      <h1 className="text-4xl font-bold tracking-tight">Dashboard</h1>
    </section>
  );
}
