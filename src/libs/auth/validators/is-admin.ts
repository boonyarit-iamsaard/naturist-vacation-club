import { auth } from '@clerk/nextjs/server';

export const isAdmin = () => {
  const { sessionClaims } = auth();

  return sessionClaims?.metadata.role === 'admin';
};
