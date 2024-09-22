import { z } from 'zod';

export const sendPasswordResetLinkSchema = z.object({
  email: z.string().email(),
});
export type SendPasswordResetLinkRequest = z.TypeOf<
  typeof sendPasswordResetLinkSchema
>;
