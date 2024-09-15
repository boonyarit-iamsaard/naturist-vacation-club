'use client';

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
import { Form } from '~/components/ui/form';
import { Input } from '~/components/ui/input';
import { Label } from '~/components/ui/label';

const updateProfileSchema = z.object({
  name: z.string(),
  email: z.string(),
});
type UpdateProfileParams = z.infer<typeof updateProfileSchema>;

export function UpdateProfileForm() {
  const form = useForm<UpdateProfileParams>({
    defaultValues: {
      name: '',
      email: '',
    },
  });

  function onSubmit(values: UpdateProfileParams) {
    console.log(values);
  }

  return (
    <Form {...form}>
      <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-4">
        <Card>
          <CardHeader>
            <CardTitle className="text-lg font-medium">
              Profile information
            </CardTitle>
            <CardDescription>
              Update your account&apos;s profile information and email address.
            </CardDescription>
          </CardHeader>
          <CardContent>
            <div className="max-w-lg space-y-4">
              <div className="space-y-2">
                <Label htmlFor="name">Name</Label>
                <Input id="name" type="text" />
              </div>
              <div className="space-y-2">
                <Label htmlFor="email">Email</Label>
                <Input id="email" type="email" />
              </div>
            </div>
          </CardContent>
          <CardFooter>
            <Button type="submit">Update profile</Button>
          </CardFooter>
        </Card>
      </form>
    </Form>
  );
}
