import { z } from 'zod';

export const signInParams = z.object({
  identifier: z.string().email('Please enter a valid email address.'),
  password: z.string().min(1, 'Please enter your password.'),
});

export type SignInParams = z.infer<typeof signInParams>;
