import { Menu, MenuItem } from "@headlessui/react";
import { Link } from "@inertiajs/react";

const DropdownLink = ({ children, ...props }) => (
    <MenuItem>
        {({ active }) => (
            <Link
                {...props}
                className={`w-full text-left block px-4 py-2 text-sm leading-5 text-gray-700 ${
                    active ? "bg-gray-100" : ""
                } focus:outline-none transition duration-150 ease-in-out`}
            >
                {children}
            </Link>
        )}
    </MenuItem>
);

export const DropdownButton = ({ children, ...props }) => (
    <MenuItem>
        {({ active }) => (
            <button
                {...props}
                className={`w-full text-left block px-4 py-2 text-sm leading-5 text-gray-700 ${
                    active ? "bg-gray-100" : ""
                } focus:outline-none transition duration-150 ease-in-out ${
                    props.className
                }`}
            >
                {children}
            </button>
        )}
    </MenuItem>
);

export default DropdownLink;
