import { PrismaAdapter } from '@lucia-auth/adapter-prisma';
import { PrismaClient, type User } from '@prisma/client';
import { Lucia, TimeSpan } from 'lucia';

import { env } from '~/env';

const client = new PrismaClient();

const adapter = new PrismaAdapter(client.session, client.user);

export const lucia = new Lucia(adapter, {
  getSessionAttributes: (/* attributes */) => {
    return {};
  },
  getUserAttributes: (attributes) => {
    return {
      id: attributes.id,
      name: attributes.name,
      email: attributes.email,
      emailVerified: attributes.emailVerified,
      image: attributes.image,
      createdAt: attributes.createdAt,
      updatedAt: attributes.updatedAt,
    };
  },
  sessionExpiresIn: new TimeSpan(30, 'd'),
  sessionCookie: {
    name: 'session',

    expires: false, // session cookies have very long lifespan (2 years)
    attributes: {
      secure: env.NODE_ENV === 'production',
    },
  },
});

declare module 'lucia' {
  interface Register {
    Lucia: typeof lucia;
    DatabaseSessionAttributes: DatabaseSessionAttributes;
    DatabaseUserAttributes: DatabaseUserAttributes;
  }
}

// eslint-disable-next-line @typescript-eslint/no-empty-interface
interface DatabaseSessionAttributes {}
type DatabaseUserAttributes = Omit<User, 'hashedPassword'>;
