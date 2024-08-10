'use client';

import Link from 'next/link';
import { usePathname } from 'next/navigation';

import { SignedIn, SignedOut, UserButton } from '@clerk/nextjs';

import { Button } from '~/components/ui/button';

export function SiteHeader() {
  const pathname = usePathname();
  const isSignInPage = pathname.includes('/sign-in');

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
          {isSignInPage ? null : (
            <SignedOut>
              <Button asChild variant="ghost">
                <Link href="/sign-in">Sign in</Link>
              </Button>
            </SignedOut>
          )}
          <SignedIn>
            <UserButton />
          </SignedIn>
        </nav>
      </div>
    </header>
  );
}
