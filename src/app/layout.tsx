import '~/styles/globals.css';

import { type ReactNode } from 'react';
import { type Metadata } from 'next';

import { ClerkProvider } from '@clerk/nextjs';

import { cn } from '~/libs/utils/cn';
import { fontSans } from '~/libs/utils/fonts';
import { TRPCReactProvider } from '~/trpc/react';

type RootLayoutProps = {
  children: ReactNode;
};

export const metadata: Metadata = {
  title: 'Naturist Vacation Club',
  description: 'Naturist Vacation Club',
  icons: [{ rel: 'icon', url: '/favicon.ico' }],
};

export default function RootLayout({ children }: RootLayoutProps) {
  return (
    <ClerkProvider>
      <html lang="en" suppressHydrationWarning>
        <body
          className={cn(
            'min-h-screen bg-background font-sans antialiased',
            fontSans.variable,
          )}
        >
          <div className="relative flex min-h-screen flex-col bg-background">
            <TRPCReactProvider>{children}</TRPCReactProvider>
          </div>
        </body>
      </html>
    </ClerkProvider>
  );
}
