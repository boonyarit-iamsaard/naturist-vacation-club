import { NextResponse } from 'next/server';

import { Scrypt } from 'lucia';
import { z } from 'zod';

import { db } from '~/server/db';

// TODO: move the schema to a shared location
const createUserSchema = z.object({
  email: z.string().email(),
  name: z.string(),
  // TODO: increase the password's strength
  password: z.string().min(8),
});

export async function POST(request: Request) {
  const parsed = createUserSchema.safeParse(await request.json());
  if (!parsed.success) {
    return NextResponse.json({ error: 'BAD_REQUEST' }, { status: 400 });
  }

  const { email, name, password } = parsed.data;
  const hashedPassword = await new Scrypt().hash(password);

  const user = await db.user.upsert({
    where: { email },
    create: {
      email,
      name,
      hashedPassword,
    },
    update: {
      name,
      hashedPassword,
    },
  });
  if (!user) {
    return NextResponse.json(
      { error: 'INTERNAL_SERVER_ERROR' },
      { status: 500 },
    );
  }

  return NextResponse.json(
    { message: 'A new user created successfully.' },
    { status: 201 },
  );
}
