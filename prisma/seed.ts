import { hash } from '@node-rs/argon2';
import { PrismaClient, Role } from '@prisma/client';
import { z } from 'zod';

const prisma = new PrismaClient();
// TODO: move the schema to a shared location
const createUserSchema = z.object({
  email: z.string().email(),
  name: z.string(),
  // TODO: increase the password's strength, maybe use regex to enforce a strong password
  password: z.string().min(8),
  role: z.nativeEnum(Role),
});
const user = {
  email: process.env.ADMIN_EMAIL,
  name: process.env.ADMIN_NAME,
  password: process.env.ADMIN_PASSWORD,
  role: process.env.ADMIN_ROLE,
};
async function main() {
  const { email, name, password, role } = createUserSchema.parse(user);
  const hashedPassword = await hash(password, {
    // A recommended minimum parameters - https://thecopenhagenbook.com/password-authentication#password-storage
    memoryCost: 19456,
    timeCost: 2,
    outputLen: 32,
    parallelism: 1,
  });

  await prisma.user.upsert({
    where: { email },
    update: {
      name,
      hashedPassword,
      role,
    },
    create: {
      email,
      name,
      hashedPassword,
      role,
    },
  });
}

main()
  .then(async () => {
    await prisma.$disconnect();
  })
  .catch(async (e) => {
    console.error(JSON.stringify(e));
    await prisma.$disconnect();
    process.exit(1);
  });
