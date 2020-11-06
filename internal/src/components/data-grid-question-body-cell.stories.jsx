import React from 'react'

import DataGridQuestionBodyCell from './data-grid-question-body-cell'

export default {
	component: DataGridQuestionBodyCell,
	title: 'DataGridQuestionBodyCell',
	parameters: {
		controls: {
			expanded: true
		}
	}
}

const Template = args => <DataGridQuestionBodyCell {...args} />

export const Text = Template.bind({})
Text.args = {
	items: [
		{
			pageItemID: 0,
			component: 'TextArea',
			data:
				'<TEXTFORMAT LEADING="2"><P ALIGN="LEFT"><FONT FACE="Arial" SIZE="14" COLOR="#393939" LETTERSPACING="0" KERNING="0">Test</FONT></P></TEXTFORMAT>',
			media: [],
			advancedEdit: 0,
			options: null,
			_explicitType: 'obo\\lo\\PageItem'
		}
	]
}

export const Media = Template.bind({})
Media.args = {
	items: [
		{
			pageItemID: 0,
			component: 'MediaView',
			data: '',
			media: [
				{
					mediaID: 3341,
					auth: '6661',
					title: 'The Pride',
					itemType: 'pic',
					descText: 'Photo from Flickr 2',
					createTime: 1344543906,
					copyright:
						'Photo used under Creative Commons from <U><A HREF="event:http://www.flickr.com/photos/79723524@N03/7745511508">AbdillahAbi</A></U>',
					thumb: '0',
					url: 'The_Pride.jpg',
					size: '174927',
					length: '0',
					perms: null,
					height: 417,
					width: 514.8,
					meta: 0,
					attribution: 1,
					_explicitType: 'obo\\lo\\Media'
				}
			],
			advancedEdit: 0,
			options: null,
			_explicitType: 'obo\\lo\\PageItem'
		}
	]
}

export const TextAndMedia = Template.bind({})
TextAndMedia.args = {
	items: [
		{
			pageItemID: 0,
			component: 'TextArea',
			data:
				'<TEXTFORMAT LEFTMARGIN="10" RIGHTMARGIN="15" LEADING="3"><P ALIGN="LEFT"><FONT FACE="Arial" SIZE="14" COLOR="#393939" LETTERSPACING="0" KERNING="0"></FONT></P></TEXTFORMAT><TEXTFORMAT LEFTMARGIN="10" RIGHTMARGIN="15" LEADING="3"><P ALIGN="LEFT"><FONT FACE="Arial" SIZE="14" COLOR="#393939" LETTERSPACING="0" KERNING="1">Given examples that include the following, students will be able to identify what constitutes plagiarism in their academic work and how to avoid the common causes of plagiarism when they use:<FONT KERNING="0"></FONT></FONT></P></TEXTFORMAT><TEXTFORMAT LEFTMARGIN="10" RIGHTMARGIN="15" LEADING="3"><P ALIGN="LEFT"><FONT FACE="Arial" SIZE="14" COLOR="#393939" LETTERSPACING="0" KERNING="0"></FONT></P></TEXTFORMAT><TEXTFORMAT LEFTMARGIN="10" RIGHTMARGIN="15" LEADING="3"><LI><FONT FACE="Arial" SIZE="14" COLOR="#393939" LETTERSPACING="0" KERNING="0">direct quotes,</FONT></LI></TEXTFORMAT><TEXTFORMAT LEFTMARGIN="10" RIGHTMARGIN="15" LEADING="3"><LI><FONT FACE="Arial" SIZE="14" COLOR="#393939" LETTERSPACING="0" KERNING="0">paraphrased text,</FONT></LI></TEXTFORMAT><TEXTFORMAT LEFTMARGIN="10" RIGHTMARGIN="15" LEADING="3"><LI><FONT FACE="Arial" SIZE="14" COLOR="#393939" LETTERSPACING="0" KERNING="0">or summarized text.</FONT></LI></TEXTFORMAT><TEXTFORMAT LEADING="2"><P ALIGN="LEFT"><FONT FACE="Arial" SIZE="14" COLOR="#393939" LETTERSPACING="0" KERNING="0"></FONT></P></TEXTFORMAT><TEXTFORMAT LEADING="2"><P ALIGN="LEFT"><FONT FACE="Arial" SIZE="14" COLOR="#393939" LETTERSPACING="0" KERNING="0"></FONT></P></TEXTFORMAT>',
			media: [],
			advancedEdit: 0,
			options: null,
			_explicitType: 'obo\\lo\\PageItem'
		},
		{
			pageItemID: 0,
			component: 'MediaView',
			data: '',
			media: [
				{
					mediaID: 3341,
					auth: '6661',
					title: 'The Pride',
					itemType: 'pic',
					descText: 'Photo from Flickr 2',
					createTime: 1344543906,
					copyright:
						'Photo used under Creative Commons from <U><A HREF="event:http://www.flickr.com/photos/79723524@N03/7745511508">AbdillahAbi</A></U>',
					thumb: '0',
					url: 'The_Pride.jpg',
					size: '174927',
					length: '0',
					perms: null,
					height: 417,
					width: 514.8,
					meta: 0,
					attribution: 1,
					_explicitType: 'obo\\lo\\Media'
				}
			],
			advancedEdit: 0,
			options: null,
			_explicitType: 'obo\\lo\\PageItem'
		}
	]
}
