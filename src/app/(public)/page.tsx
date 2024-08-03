import Link from 'next/link';

import { LatestPost } from '~/app/(public)/_components/post';
import { Button } from '~/components/ui/button';
import { getServerAuthSession } from '~/server/auth';
import { api, HydrateClient } from '~/trpc/server';

export default async function Home() {
  const greeting = await api.post.greeting();
  const session = await getServerAuthSession();

  void api.post.getLatest.prefetch();

  return (
    <HydrateClient>
      <div className="container flex flex-col items-center gap-8 py-8 md:py-16">
        <h1 className="text-2xl font-extrabold tracking-tight md:text-4xl">
          {greeting ? greeting.message : 'Loading tRPC query...'}
        </h1>

        <div className="flex flex-col items-center justify-center gap-4">
          {session && (
            <p className="text-center text-2xl">
              Logged in as {session.user?.name}
            </p>
          )}
          <Button asChild>
            <Link href={session ? '/api/auth/signout' : '/api/auth/signin'}>
              {session ? 'Sign out' : 'Sign in'}
            </Link>
          </Button>
        </div>

        {session?.user && <LatestPost />}
      </div>
    </HydrateClient>
  );
}
