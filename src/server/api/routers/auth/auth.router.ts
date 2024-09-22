import { sendPasswordResetLinkSchema } from '~/server/api/routers/auth/auth.schema';
import { sendPasswordResetLink } from '~/server/api/routers/auth/auth.service';
import { createTRPCRouter, publicProcedure } from '~/server/api/trpc';

export const authRouter = createTRPCRouter({
  sendPasswordResetLink: publicProcedure
    .input(sendPasswordResetLinkSchema)
    .mutation(({ ctx, input }) => sendPasswordResetLink(ctx, input)),
});
