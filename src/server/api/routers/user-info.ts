import { z } from 'zod';

import { adminProcedure, createTRPCRouter } from '~/server/api/trpc';

export const userInfoRouter = createTRPCRouter({
  findByUserId: adminProcedure
    .input(z.object({ userId: z.string().uuid() }))
    .query(({ ctx, input }) => {
      return ctx.db.userInfo.findUnique({
        where: { userId: input.userId },
      });
    }),
});
