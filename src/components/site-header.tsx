import Link from 'next/link';

import { SignedIn, SignedOut } from '@clerk/nextjs';
import { auth } from '@clerk/nextjs/server';

import { ProfileButton } from '~/components/profile-button';
import { Button } from '~/components/ui/button';

export function SiteHeader() {
  const { sessionClaims } = auth();
  const role = sessionClaims?.metadata?.role;

  return (
    <header className="sticky top-0 z-50 w-full border-border/40 bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
      <div className="container flex h-14 items-center">
        <div className="flex flex-1 items-center justify-between space-x-2">
          <Link
            href="/"
            className="text-sm font-bold text-foreground transition-colors hover:text-foreground/80"
          >
            Home
          </Link>
        </div>
        <nav className="flex items-center gap-4 text-sm font-medium">
          <SignedOut>
            <Button asChild variant="ghost" className="h-8 px-2 py-1.5">
              <Link
                href="/sign-in"
                className="transition-colors hover:text-foreground/80"
                prefetch
              >
                Sign in
              </Link>
            </Button>
          </SignedOut>
          <SignedIn>
            <ProfileButton role={role} />
          </SignedIn>
        </nav>
      </div>
    </header>
  );
}
