'use client';

import { useState, type ChangeEvent } from 'react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';

import { useSignIn } from '@clerk/nextjs';
import { Loader2 } from 'lucide-react';
import { useForm } from 'react-hook-form';

import { Button } from '~/components/ui/button';
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '~/components/ui/card';
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '~/components/ui/form';
import { Input } from '~/components/ui/input';
import { type SignInParams } from '~/libs/auth/validators';

export function SignInForm() {
  const { isLoaded, signIn, setActive } = useSignIn();
  const [loading, setLoading] = useState<boolean>(false);
  const [signInErrorMessage, setSignInErrorMessage] = useState<string | null>(
    null,
  );
  const router = useRouter();
  const form = useForm<SignInParams>({
    defaultValues: {
      identifier: '',
      password: '',
    },
  });

  function handleEmailChange(event: ChangeEvent<HTMLInputElement>) {
    form.setValue('identifier', event.target.value);
    form.clearErrors('identifier');
    setSignInErrorMessage(null);
  }

  function handlePasswordChange(event: ChangeEvent<HTMLInputElement>) {
    form.setValue('password', event.target.value);
    form.clearErrors('password');
    setSignInErrorMessage(null);
  }

  async function onSubmit(values: SignInParams) {
    if (!isLoaded) {
      return;
    }

    setLoading(true);
    setSignInErrorMessage(null);

    try {
      const { identifier, password } = values;
      const signInAttempt = await signIn.create({
        identifier,
        password,
      });

      if (signInAttempt.status === 'complete') {
        await setActive({ session: signInAttempt.createdSessionId });
        router.replace('/');
      } else {
        setLoading(false);
        // TODO: handle error messages
        console.error(JSON.stringify(signInAttempt, null, 2));
      }
    } catch (err: unknown) {
      setLoading(false);
      // TODO: handle error messages
      // See https://clerk.com/docs/custom-flows/error-handling
      // for more info on error handling
      console.error(JSON.stringify(err, null, 2));
    }
  }

  // TODO: improve form title and description
  return (
    <Form {...form}>
      <form onSubmit={form.handleSubmit(onSubmit)} className="mx-auto max-w-md">
        <Card>
          <CardHeader className="md:p-8">
            <CardTitle className="text-lg md:text-2xl">
              Sign in to Naturist Vacation Club
            </CardTitle>
            <CardDescription>
              Welcome back, Please sign in to continue.
            </CardDescription>
          </CardHeader>
          <CardContent className="md:p-8 md:pt-0">
            <div className="grid gap-4">
              <FormField
                control={form.control}
                name="identifier"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>Email</FormLabel>
                    <FormControl>
                      <Input
                        {...field}
                        autoComplete="email"
                        disabled={loading}
                        placeholder="email@example.com"
                        type="email"
                        value={field.value}
                        onChange={handleEmailChange}
                      />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />

              <FormField
                control={form.control}
                name="password"
                render={({ field }) => (
                  <FormItem>
                    <div className="flex items-center">
                      <FormLabel>Password</FormLabel>
                      <Link
                        href="#"
                        className="ml-auto inline-block text-sm underline"
                      >
                        Forgot your password?
                      </Link>
                    </div>
                    <FormControl>
                      <Input
                        {...field}
                        autoComplete="current-password"
                        disabled={loading}
                        type="password"
                        value={field.value}
                        onChange={handlePasswordChange}
                      />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />
            </div>

            {signInErrorMessage ? (
              <div className="mt-4 rounded-md border border-destructive/50 bg-destructive/10 px-4 py-2 text-center text-sm text-destructive">
                {signInErrorMessage}
              </div>
            ) : null}
          </CardContent>
          <CardFooter className="md:p-8 md:pt-0">
            <Button type="submit" className="w-full">
              {loading ? (
                <Loader2 className="mr-2 h-4 w-4 animate-spin" />
              ) : null}
              Sign in
            </Button>
          </CardFooter>
        </Card>
      </form>
    </Form>
  );
}
