'use client'

export default function Table({ columns, data,action }){
    return <div className="rounded-lg relative w-full overflow-auto">
        <table className="rounded-lg min-w-full bg-white">
            <thead>
                <tr className="w-full bg-gray-200 text-gray-800">
                    {columns.map((column, index) => (
                        <th key={index} className="text-center py-2 px-4 border-b">
                            {column}
                        </th>
                    ))}
                </tr>
            </thead>
            <tbody>
                {data.map((row, rowIndex) => (
                    <tr onClick={()=>action(row)} key={rowIndex} className="my-[5px] hover:transition-300 hover:bg-gray-300 hover:cursor-pointer text-gray-700">
                        {columns.map((column, colIndex) => (
                            <td key={colIndex} className="text-center py-2 px-4 border-b-2">
                                    {row[column]}
                            </td>
                        ))}
                    </tr>
                ))}
            </tbody>
        </table>
    </div>
}