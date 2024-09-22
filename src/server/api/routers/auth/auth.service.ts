import { env } from '~/env';
import { EmailTemplate, sendMail } from '~/libs/email';
import { type SendPasswordResetLinkRequest } from '~/server/api/routers/auth/auth.schema';
import { type PublicContext } from '~/server/api/trpc';

export async function sendPasswordResetLink(
  _ctx: PublicContext,
  input: SendPasswordResetLinkRequest,
) {
  try {
    // TODO: implement following logic
    // - add generate password reset token function
    // - verify if user exists
    // - if user exists, send email otherwise return error
    // e.g., const verificationToken = await generatePasswordResetToken(user.id);
    const verificationToken = 'zLxE2mLAyC0tEgEX';

    const verificationLink = `${env.NEXT_PUBLIC_APP_URL}/reset-password/${verificationToken}`;

    await sendMail(input.email, EmailTemplate.PasswordReset, {
      link: verificationLink,
    });

    return {
      success: true,
    };
  } catch (error) {
    // TODO: improve error handling
    return {
      error,
    };
  }
}
