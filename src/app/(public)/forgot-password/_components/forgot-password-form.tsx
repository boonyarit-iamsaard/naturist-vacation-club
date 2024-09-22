'use client';

import { type ChangeEvent } from 'react';

import { zodResolver } from '@hookform/resolvers/zod';
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
import {
  sendPasswordResetLinkSchema,
  type SendPasswordResetLinkRequest,
} from '~/server/api/routers/auth/auth.schema';
import { api } from '~/trpc/react';

export function ForgotPasswordForm() {
  const form = useForm<SendPasswordResetLinkRequest>({
    defaultValues: {
      email: '',
    },
    resolver: zodResolver(sendPasswordResetLinkSchema),
  });

  const sendPasswordResetLink = api.auth.sendPasswordResetLink.useMutation({
    onError() {
      form.reset();
    },
    onSuccess() {
      form.reset();
    },
  });

  function handleEmailChange(event: ChangeEvent<HTMLInputElement>) {
    form.setValue('email', event.target.value);
    form.clearErrors('email');
  }

  function onSubmit(values: SendPasswordResetLinkRequest) {
    sendPasswordResetLink.mutate(values);
  }

  return (
    <Form {...form}>
      <form onSubmit={form.handleSubmit(onSubmit)} className="mx-auto max-w-md">
        <Card>
          <CardHeader className="md:p-8">
            <CardTitle className="text-lg md:text-2xl">
              Reset Your Password
            </CardTitle>
            <CardDescription>
              Enter your email address to receive a password reset link.
            </CardDescription>
          </CardHeader>
          <CardContent className="md:p-8 md:pt-0">
            <div className="grid gap-4">
              <FormField
                control={form.control}
                name="email"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>Email</FormLabel>
                    <FormControl>
                      <Input
                        {...field}
                        autoComplete="email"
                        disabled={sendPasswordResetLink.isPending}
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
            </div>
            {sendPasswordResetLink.isError ? (
              <div className="mt-4 rounded-md border border-destructive/50 bg-destructive/10 px-4 py-2 text-center text-sm text-destructive">
                {sendPasswordResetLink.error.message}
              </div>
            ) : null}
          </CardContent>
          <CardFooter className="md:p-8 md:pt-0">
            <Button type="submit" className="w-full">
              {sendPasswordResetLink.isPending ? (
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
