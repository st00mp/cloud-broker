export default function Navbar() {
    return (
        <nav className="bg-gray-900 text-white p-4">
            <div className="container mx-auto flex justify-between items-center">
                <a href="/" className="text-xl font-bold">Cloud Broker</a>
                <ul className="flex space-x-4">
                    <li><a href="/" className="hover:text-gray-400">Home</a></li>
                    <li><a href="/test" className="hover:text-gray-400">Test</a></li>
                    <li><a href="/about" className="hover:text-gray-400">About</a></li>
                </ul>
            </div>
        </nav>
    );
}