import Link from 'next/link';

import { Button } from '~/components/ui/button';
import { logout } from '~/libs/auth/action';
import { validateRequest } from '~/libs/auth/validate-request';
import { api, HydrateClient } from '~/trpc/server';

export default async function Home() {
  const greeting = await api.greeting();
  const { user } = await validateRequest();

  return (
    <HydrateClient>
      <div className="container flex flex-col items-center gap-8 py-8 md:py-16">
        <h1 className="text-2xl font-extrabold tracking-tight md:text-4xl">
          {greeting ? greeting.message : 'Loading tRPC query...'}
        </h1>

        <div className="flex flex-col items-center justify-center gap-4">
          {user ? (
            <p className="text-center text-2xl">Logged in as {user.name}</p>
          ) : null}

          {user ? (
            <form action={logout}>
              <Button>Logout</Button>
            </form>
          ) : (
            <Button asChild>
              <Link href="/login">Login</Link>
            </Button>
          )}
        </div>
      </div>
    </HydrateClient>
  );
}
