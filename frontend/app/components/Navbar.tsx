"use client" // pour interactions coté client

import Link from "next/link";
import {
    NavigationMenu,
    NavigationMenuItem,
    NavigationMenuList,
} from "@/components/ui/navigation-menu"

export default function Navbar() {
    return (
        <nav className="bg-background text-foreground p-4 border-b border-gray-800">
            <div className="container mx-auto flex justify-between items-center">
                <Link href="/" className="text-xl font-bold hover:opacity-80">Cloud Broker 👾</Link>

                <NavigationMenu>
                    <NavigationMenuList>
                        <NavigationMenuItem>
                            <Link href="http://localhost:8080/api/gpu/offers" className="px-4 py-2 hover:bg-gray-700 rounded-md">API</Link>
                        </NavigationMenuItem>
                        <NavigationMenuItem>
                            <Link href="/blog" className="px-4 py-2 hover:bg-gray-700 rounded-md">Blog</Link>
                        </NavigationMenuItem>
                        <NavigationMenuItem>
                            <Link href="/about" className="px-4 py-2 hover:bg-gray-700 rounded-md">About</Link>
                        </NavigationMenuItem>
                    </NavigationMenuList>
                </NavigationMenu>
            </div>
        </nav>
    );
}