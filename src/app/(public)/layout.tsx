import { type ReactNode } from 'react';

type PublicLayoutProps = {
  children: ReactNode;
};

export default function PublicLayout({ children }: PublicLayoutProps) {
  return <main className="flex-1">{children}</main>;
}
