'use client';

import Link from 'next/link';
import { useRouter } from 'next/navigation';

import { useSignIn } from '@clerk/nextjs';
import { useForm } from 'react-hook-form';
import { z } from 'zod';

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

const signInParams = z.object({
  identifier: z.string().email('Please enter a valid email address.'),
  password: z.string().min(1, 'Please enter your password.'),
});

type SignInParams = z.infer<typeof signInParams>;

export function SignInForm() {
  const { isLoaded, signIn, setActive } = useSignIn();
  const router = useRouter();
  const form = useForm<SignInParams>({
    defaultValues: {
      identifier: '',
      password: '',
    },
  });

  // TODO: add loading state handling
  const onSubmit = async (values: SignInParams) => {
    if (!isLoaded) {
      return;
    }

    const { identifier, password } = values;
    try {
      const signInAttempt = await signIn.create({
        identifier,
        password,
      });

      if (signInAttempt.status === 'complete') {
        await setActive({ session: signInAttempt.createdSessionId });
        router.replace('/');
      } else {
        // TODO: improve error handling
        console.error(JSON.stringify(signInAttempt, null, 2));
      }
    } catch (err: unknown) {
      // TODO: improve error handling
      console.error(JSON.stringify(err, null, 2));
    }
  };

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
                        autoComplete="email"
                        placeholder="email@example.com"
                        type="email"
                        {...field}
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
                        autoComplete="current-password"
                        type="password"
                        {...field}
                      />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />
            </div>
          </CardContent>
          <CardFooter className="md:p-8 md:pt-0">
            <Button type="submit" className="w-full">
              Login
            </Button>
          </CardFooter>
        </Card>
      </form>
    </Form>
  );
}
