/* eslint-disable react/jsx-key */

import React from 'react'
import { useTable, useSortBy } from 'react-table'
import PropTypes from 'prop-types'
import CaretUp from '../../../assets/images/viewer/caret-up.svg'
import CaretDown from '../../../assets/images/viewer/caret-down.svg'

import './data-grid.scss'
import LoadingIndicator from './loading-indicator'

const DataGrid = ({ data, columns, sortable, selectedIndex, onSelect }) => {
	const isLoading = data === null

	// setup react-table
	const instanceTable = useTable(
		{
			columns,
			data: data || []
		},
		useSortBy
	)

	const { getTableProps, getTableBodyProps, headerGroups, rows, prepareRow } = instanceTable

	return (
		<table
			{...getTableProps()}
			className={`repository--data-grid ${onSelect ? 'selectable' : ''} ${
				sortable ? 'sortable' : ''
			}`}
		>
			<thead>
				{headerGroups.map(headerGroup => (
					<tr {...headerGroup.getHeaderGroupProps()}>
						{headerGroup.headers.map(column => (
							<th
								{...column.getHeaderProps(
									sortable && !isLoading ? column.getSortByToggleProps() : {}
								)}
							>
								{column.render('Header')}
								{column.isSorted && column.isSortedDesc ? <CaretUp /> : null}
								{column.isSorted && !column.isSortedDesc ? <CaretDown /> : null}
							</th>
						))}
					</tr>
				))}
			</thead>
			<tbody {...getTableBodyProps()}>
				{isLoading || !rows.length ? (
					<tr>
						<td className="no-data" colSpan={columns.length}>
							{isLoading ? <LoadingIndicator isLoading={true} /> : 'No data'}
						</td>
					</tr>
				) : (
					rows.map(row => {
						prepareRow(row)

						const className = row.index === selectedIndex ? 'selected' : ''
						const onClick = () => {
							if (!onSelect) return
							onSelect(row.index)
						}
						return (
							<tr {...row.getRowProps()} onClick={onClick} className={className}>
								{row.cells.map(cell => (
									<td {...cell.getCellProps()}>{cell.render('Cell')}</td>
								))}
							</tr>
						)
					})
				)}
			</tbody>
		</table>
	)
}

DataGrid.defaultProps = {
	data: null,
	columns: [],
	sortable: true
}

DataGrid.propTypes = {
	data: PropTypes.oneOfType([null, PropTypes.arrayOf(PropTypes.object)]),
	columns: PropTypes.arrayOf(PropTypes.object),
	sortable: PropTypes.bool,
	selectedIndex: PropTypes.oneOfType([null, PropTypes.number]),
	onSelect: PropTypes.func.isRequired
}

export default DataGrid
