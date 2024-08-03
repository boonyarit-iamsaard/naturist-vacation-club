'use client';

import Link from 'next/link';

import { useFormState } from 'react-dom';

import { Button } from '~/components/ui/button';
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '~/components/ui/card';
import { Input } from '~/components/ui/input';
import { Label } from '~/components/ui/label';
import { login } from '~/libs/auth/action';

export function LoginForm() {
  const [state, formAction] = useFormState(login, null);

  return (
    <form action={formAction} className="mx-auto max-w-sm">
      <Card>
        <CardHeader>
          <CardTitle className="text-2xl">Login</CardTitle>
          <CardDescription>
            Enter your email below to login to your account
          </CardDescription>
        </CardHeader>
        <CardContent>
          <div className="grid gap-4">
            <div className="space-y-2">
              <Label htmlFor="email">Email</Label>
              <Input
                required
                id="email"
                placeholder="email@example.com"
                autoComplete="email"
                name="email"
                type="email"
              />
            </div>

            <div className="space-y-2">
              <div className="flex items-center">
                <Label>Password</Label>
                <Link
                  href="#"
                  className="ml-auto inline-block text-sm underline"
                >
                  Forgot your password?
                </Link>
              </div>
              <Input
                id="password"
                name="password"
                type="password"
                required
                autoComplete="current-password"
                placeholder="********"
              />
            </div>

            <Button type="submit" className="w-full">
              Login
            </Button>

            {state?.fieldError ? (
              <ul className="space-y-1 rounded-md border border-destructive/20 bg-destructive/10 px-3 py-2 text-sm font-medium text-destructive">
                {Object.values(state.fieldError).map((err) => (
                  <li key={err}>{err}</li>
                ))}
              </ul>
            ) : state?.formError ? (
              <p className="rounded-md border border-destructive/20 bg-destructive/10 px-3 py-2 text-sm font-medium text-destructive">
                {state?.formError}
              </p>
            ) : null}
          </div>
        </CardContent>
      </Card>
    </form>
  );
}
