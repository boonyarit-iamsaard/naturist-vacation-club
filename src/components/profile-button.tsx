'use client';

import { useClerk, useUser } from '@clerk/nextjs';
import { Calendar, LogOut, ShieldCheckIcon, User } from 'lucide-react';

import { Button } from '~/components/ui/button';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuGroup,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '~/components/ui/dropdown-menu';
import { Skeleton } from '~/components/ui/skeleton';

type ProfileButtonProps = {
  role?: CustomJwtSessionClaims['metadata']['role'];
};

export function ProfileButton({ role }: Readonly<ProfileButtonProps>) {
  const { signOut } = useClerk();
  const { isSignedIn, user, isLoaded } = useUser();

  async function handleSignOut() {
    await signOut({ redirectUrl: '/' });
  }

  return (
    <DropdownMenu>
      <DropdownMenuTrigger asChild>
        <Button
          variant="ghost"
          className="h-8 w-8 rounded-full bg-accent/40 px-0"
        >
          <User className="h-4 w-4" />
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent align="end" className="w-60">
        <DropdownMenuLabel className="flex flex-col">
          {!isLoaded ? <Skeleton className="h-9" /> : null}
          {isSignedIn ? (
            <>
              <span>{user?.fullName}</span>
              <span className="text-xs font-normal text-muted-foreground">
                {user?.primaryEmailAddress?.emailAddress}
              </span>
            </>
          ) : null}
        </DropdownMenuLabel>
        <DropdownMenuSeparator />
        <DropdownMenuGroup>
          <DropdownMenuItem>
            <User className="mr-2 h-4 w-4" />
            <span>Manage account</span>
          </DropdownMenuItem>
          <DropdownMenuItem>
            <Calendar className="mr-2 h-4 w-4" />
            <span>Bookings</span>
          </DropdownMenuItem>
          {role === 'admin' ? (
            <DropdownMenuItem>
              <ShieldCheckIcon className="mr-2 h-4 w-4" />
              <span>Admin</span>
            </DropdownMenuItem>
          ) : null}
        </DropdownMenuGroup>
        <DropdownMenuSeparator />
        <DropdownMenuItem onClick={handleSignOut}>
          <LogOut className="mr-2 h-4 w-4" />
          <span>Sign out</span>
        </DropdownMenuItem>
      </DropdownMenuContent>
    </DropdownMenu>
  );
}
