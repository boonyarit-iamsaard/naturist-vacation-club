import { Button } from '~/components/ui/button';

export default function Page() {
  return (
    <section className="container flex flex-col items-center gap-4 py-8">
      <h1 className="text-4xl font-bold tracking-tight">
        Welcome to Naturist Vacation Club
      </h1>
      <Button>Click me</Button>
    </section>
  );
}
