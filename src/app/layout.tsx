import '~/styles/globals.css';

import { type ReactNode } from 'react';
import { type Metadata } from 'next';

import { NextAuthProvider } from '~/app/providers/next-auth-provider';
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

export default function RootLayout({ children }: Readonly<RootLayoutProps>) {
  return (
    <html lang="en" suppressHydrationWarning>
      <body
        className={cn(
          'min-h-screen bg-background font-sans antialiased',
          fontSans.variable,
        )}
      >
        <div className="relative flex min-h-screen flex-col bg-background">
          <NextAuthProvider>
            <TRPCReactProvider>{children}</TRPCReactProvider>
          </NextAuthProvider>
        </div>
      </body>
    </html>
  );
}
