import './data-grid-student-score-cell.scss'

import React from 'react'
import PropTypes from 'prop-types'
import Button from './button'

export default function DataGridStudentScoreCell({ value, row, column }) {
	const { onClick } = column

	const click = React.useCallback(() => {
		onClick(row.original.user, row.original.userID)
	}, [row, onClick])

	return (
		<div className="data-grid-student-score-cell">
			<span className="data-grid-student-score-cell--score">{`${Math.round(value)}%` ?? '--'}</span>
			{row.original.isScoreImported ? (
				<span className="data-grid-student-score-cell--imported-text">(Imported)</span>
			) : (
				<Button type="text-bold" text="Details..." onClick={click} />
			)}
		</div>
	)
}

DataGridStudentScoreCell.defaultProps = {
	isScoreImported: false
}

DataGridStudentScoreCell.propTypes = {
	value: PropTypes.number,
	row: PropTypes.object,
	column: PropTypes.object
}
