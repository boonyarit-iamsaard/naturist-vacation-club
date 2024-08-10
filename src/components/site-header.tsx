import Link from 'next/link';

import { SignedIn, SignedOut, SignInButton, UserButton } from '@clerk/nextjs';

import { Button } from '~/components/ui/button';

export function SiteHeader() {
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
            <Button asChild variant="ghost">
              <SignInButton />
            </Button>
          </SignedOut>
          <SignedIn>
            <UserButton />
          </SignedIn>
        </nav>
      </div>
    </header>
  );
}
